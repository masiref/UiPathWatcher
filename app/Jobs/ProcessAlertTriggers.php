<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Notification;
use App\Library\Services\AlertTriggerService;
use App\Notifications\AlertTriggered;
use App\AlertTrigger;
use App\Alert;
use App\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

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

            // get current date
            $now = Carbon::now();

            Log::info('Starting processing of alert triggers');

            // retrieve all active and not ignored triggers
            $triggers = AlertTrigger::all()->where('active', true)->where('ignored', false)->where('deleted', false);

            foreach ($triggers as $trigger) {
                
                Log::info("Processing alert trigger {$trigger->id}");

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
                            
                            Log::info("All rules are verified for definition {$definition->id}");

                            $alertCreated = false;

                            if (!$existingAlert) {
                                // if there is no existing alert
                                // creation of a new alert when latest closed alert (if present) was within defined duration
                                $latestClosedAlert = $trigger->closedAlerts()->last();
                                if (!$latestClosedAlert ||
                                    ($latestClosedAlert && Carbon::createFromFormat('Y-m-d H:i:s', $latestClosedAlert->closed_at)->diffInMinutes(Carbon::now()) > env('APP_ALERT_DELAY_TO_TRIGGER_AFTER_CLOSING'))
                                ) {
                                    
                                    Log::info("Creation of a new alert for definition {$definition->id} of trigger {$trigger->id}");

                                    $alert = Alert::create([
                                        'alert_trigger_id' => $trigger->id,
                                        'alert_trigger_definition_id' => $definition->id,
                                        'watched_automated_process_id' => $wap->id,
                                        'messages' => $messages
                                    ]);
                                    $alertCreated = true;
                                }
                            } elseif ($existingAlert->definition->level !== $definition->level) {
                                
                                Log::info("Creation of a new alert and closing ancestor {$existingAlert->id} for definition {$definition->id} of trigger {$trigger->id}");

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
                                $alertCreated = true;

                                // closing of existing alert if present
                                $existingAlert->update([
                                    'closed' => true,
                                    'closed_at' => $alert->created_at,
                                    'closing_description' => 'parent alert created',
                                    'auto_closed' => true,
                                    'under_revision' => false,
                                    'parent_id' => $alert->id
                                ]);
                            } else {
                                    
                                Log::info("Updating alert {$existingAlert->id} heartbeat");
                                
                                // alert of same definition level => update heartbeat
                                $existingMessages = $existingAlert->messages;
                                $messages  = array_merge($messages, $existingMessages);
                                $existingAlert->update([
                                    'messages' => $messages,
                                    'alive' => true,
                                    'latest_heartbeat_at' => Carbon::now()
                                ]);
                            }

                            if ($alertCreated) {
                                Notification::send(User::all(), new AlertTriggered($alert));
                            }

                            // no need to check other definitions
                            break;
                        } else {
                                    
                            Log::info("At least one rule is not verified for definition {$definition->id}");

                            // if existing opened alert not alive for more than 5 minutes close it
                            if ($existingAlert) {
                                $date = Carbon::createFromFormat('Y-m-d H:i:s', $existingAlert->latest_heartbeat_at ?? $existingAlert->created_at);
                                $delay = $date->diffInMinutes(Carbon::now());
                                $delayWhenAlertSilent = env('APP_ALERT_LIFETTIME_WHEN_SILENT');

                                if ($delay > env('APP_ALERT_LIFETTIME_WHEN_SILENT') && !$existingAlert->alive) {
                                    
                                    Log::info("Closing alert {$existingAlert->id} because of no applicable definition");
                                    
                                    $existingAlert->update([
                                        'closed' => true,
                                        'closed_at' => Carbon::now(),
                                        'closing_description' => "there is no applicable definition anymore after {$delayWhenAlertSilent} minutes",
                                        'auto_closed' => true,
                                        'under_revision' => false
                                    ]);
                                } else {
                                    
                                    Log::info("Killing alert {$existingAlert->id} because not alive anymore");

                                    $existingAlert->update([
                                        'alive' => false
                                    ]);
                                }
                            }
                        }
                    }
                } else {
                    // running period is over
                    foreach ($trigger->alerts->where('closed', false)->where('parent', null)->where('alive', true) as $alert) {

                        Log::info("Closing alert {$alert->id} because running period is over");

                        $existingMessages = $alert->messages;
                        $heartbeat = Carbon::now();
                        $messages  = array_merge([
                            [
                                "{$now->format('d/m/Y H:i:s')}",
                                "Automated watched process running period is now over, bye"
                            ]
                        ], $existingMessages ?? array());
                        $alert->update([
                            'messages' => $messages,
                            'alive' => false
                        ]);
                    }
                }
            }
            
            Log::info('End processing of alert triggers');
        }
    }
}
