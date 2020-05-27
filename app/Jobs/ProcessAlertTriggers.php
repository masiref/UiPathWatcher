<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Library\Services\AlertTriggerService;
use App\AlertTrigger;
use App\Alert;
use Carbon\Carbon;

class ProcessAlertTriggers implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle(AlertTriggerService $service)
    {
        // if a shutdown of alert triggers is currently happening
        // don't do nothing else ...'
        if (!$service->isUnderShutdown()) {

            // retrieve all active and not ignored triggers
            $triggers = AlertTrigger::all()->where('active', true)->where('ignored', false)->where('deleted', false);

            // get current date
            $now = Carbon::now();

            foreach ($triggers as $trigger) {
                // get watched automated process attached to trigger
                $wap = $trigger->watchedAutomatedProcess;

                // if current date is in process running period
                if ($wap->runningOnDate($now)) {
                    
                    // get definitions attached to trigger ordered by rank (asc) and level (desc)
                    $definitions = $trigger->definitions
                        ->where('deleted', false)
                        ->sortBy('rank')
                        ->sortBy(function($definition) {
                            return $definition->levelOrder();
                        });

                    // newly created alert initialization
                    $alert = null;

                    foreach ($definitions as $definition) {
                        
                        // definition rules are by default not verified
                        $verified = false;
                        $messages = array();

                        $rules = $definition->rules
                            ->where('deleted', false)
                            ->sortBy('rank');

                        foreach ($rules as $rule) {
                            $ruleVerification = $service->verifyRule($rule, $now);
                            $verified = $ruleVerification['result'];
                            // if rule is not verified, no need to check other rules
                            if (!$verified) {
                                break;
                            } else {
                                $messages = array_merge($messages, $ruleVerification['messages']);
                            }
                        }
                            
                        // get existing opened alert attached to trigger
                        $existingAlert = $trigger->openedAlerts()->first();

                        // if all rules are verified
                        if ($verified) {

                            if (!$existingAlert) {
                                // if there is no existing alert
                                // creation of a new alert
                                $alert = Alert::create([
                                    'alert_trigger_id' => $trigger->id,
                                    'alert_trigger_definition_id' => $definition->id,
                                    'watched_automated_process_id' => $wap->id,
                                    'messages' => $messages
                                ]);
                            } elseif ($existingAlert->definition->level !== $definition->level) {
                                // if existing alert has not same definition level
                                // creation of a new alert with existing one information
                                $alert = Alert::create([
                                    'alert_trigger_id' => $trigger->id,
                                    'alert_trigger_definition_id' => $definition->id,
                                    'watched_automated_process_id' => $wap->id,
                                    'messages' => $messages,
                                    'reviewer_id' => $existingAlert->reviewer_id,
                                    'under_revision' => $existingAlert->under_revision,
                                    'revision_started_at' => $existingAlert->revision_started_at,
                                    'top_ancestor_created_at' => $existingAlert->top_ancestor_created_at ?? $existingAlert->created_at
                                ]);
                            
                                // closing of existing alert if present
                                $existingAlert->update([
                                    'closed' => true,
                                    'closed_at' => $alert->created_at,
                                    'closing_description' => 'Parent alert created',
                                    'auto_closed' => true,
                                    'under_revision' => false,
                                    'parent_id' => $alert->id
                                ]);
                            } else {
                                // alert of same definition level => update heartbeat
                                $existingAlert->update([
                                    'alive' => true,
                                    'latest_heartbeat_at' => Carbon::now()
                                ]);
                            }

                            // no need to check other definitions
                            break;
                        } else {
                            // if existing opened alert not alive for more than 5 minutes close it
                            if ($existingAlert && $existingAlert->definition->level === $definition->level) {
                                $date = Carbon::createFromFormat('Y-m-d H:i:s', $existingAlert->latest_heartbeat_at ?? $existingAlert->created_at);
                                $delay = $date->diffInMinutes(Carbon::now());

                                if ($delay > 5) {
                                    $alert->update([
                                        'closed' => true,
                                        'closed_at' => Carbon::now(),
                                        'closing_description' => 'There is no applicable definition anymore after 5 minutes',
                                        'auto_closed' => true,
                                        'under_revision' => false
                                    ]);
                                } else {
                                    $existingAlert->update([
                                        'alive' => false
                                    ]);
                                }
                            }
                        }
                    }
                }
            }
        }
    }
}
