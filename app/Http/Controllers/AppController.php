<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\AlertTriggerShutdown;
use App\Library\Services\AlertTriggerService;
use App\Library\Services\UiPathOrchestratorService;
use Illuminate\Support\Facades\Auth;
use GuzzleHttp\Client as Guzzle;
use GuzzleHttp\Exception\RequestException;
use Carbon\Carbon;
use App\AlertTrigger;
use App\Alert;

class AppController extends Controller
{

    public function shutdownAlertTriggers(Request $request, AlertTriggerService $service)
    {
        $reason = $request->get('reason');
        if (!$service->isUnderShutdown()) {
            return AlertTriggerShutdown::create([
                'reason' => $reason
            ]);
        }
        return null;
    }

    public function reactivateAlertTriggers(Request $request, AlertTriggerService $service)
    {
        $reason = $request->get('reason');
        if ($service->isUnderShutdown()) {
            $shutdown = $service->currentShutdown();
            if ($shutdown->update([
                'ended_at' => Carbon::now(),
                'ended_reason' => $reason
            ])) {
                return $shutdown;
            };
        }
        return null;
    }

    public function debug(AlertTriggerService $service)
    {
        $messages = array();
        if (!$service->isUnderShutdown()) {
            $triggers = AlertTrigger::all()->where('active', true)->where('ignored', false);
            $now = Carbon::now();
            foreach ($triggers as $trigger) {
                $wap = $trigger->watchedAutomatedProcess;
                if ($wap->runningOnDate($now)) {
                    array_push($messages, "$now in $wap running period");
                    $definitions = $trigger->definitions->sortByDesc(function($definition) {
                        return $definition->levelOrder();
                    })->sortBy('rank');
                    $alert = null;
                    foreach ($definitions as $definition) {
                        array_push($messages, "verifying definition {$definition->rank}");
                        $verified = false;
                        $rules = $definition->rules;
                        foreach ($rules as $rule) {
                            array_push($messages, "verifying rule {$rule->rank}");
                            $verified = $service->verifyRule($rule);
                            if (!$verified) {
                                break;
                            }
                        }
                        if ($verified) {
                            $existingAlert = $trigger->openedAlerts()->first();
                            if ($existingAlert) {
                                array_push($messages, "existing alert {$existingAlert->id}");
                            }
                            $alert = Alert::create([
                                'alert_trigger_id' => $trigger->id,
                                'alert_trigger_definition_id' => $definition->id,
                                'watched_automated_process_id' => $wap->id,
                                'reviewer_id' => $existingAlert ? $existingAlert->reviewer_id : null,
                                'under_revision' => $existingAlert ? $existingAlert->under_revision : false,
                                'revision_started_at' => $existingAlert ? $existingAlert->revision_started_at : null
                            ]);
                            array_push($messages, "$alert->id created");
                            if ($existingAlert) {
                                $existingAlert->update([
                                    'closed' => true,
                                    'closed_at' => $alert->created_at,
                                    'closing_description' => 'Parent alert created',
                                    'under_revision' => false,
                                    'parent_id' => $alert->id
                                ]);
                                array_push($messages, "{$alert->id} updated");
                            }
                            break;
                        }
                    }
                } else {
                    array_push($messages, "$now not in $wap running period");
                }
            }
        } else {
            array_push($messages, 'Under shutdown');
        }
        return json_encode($messages);
    }
}
