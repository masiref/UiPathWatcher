<?php

namespace App\Http\Controllers\Entity;

use App\Http\Controllers\Controller;
use App\WatchedAutomatedProcess;
use App\UiPathProcess;
use App\UiPathRobot;
use App\UiPathQueue;
use App\AlertTrigger;
use App\Library\Services\UiPathOrchestratorService;
use App\Library\Services\ElasticSearchService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class WatchedAutomatedProcessController extends Controller
{
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, UiPathOrchestratorService $orchestratorService, ElasticSearchService $elasticSearchService)
    {
        $wap = WatchedAutomatedProcess::create($request->all());
        if ($wap->save()) {
            $processes = $request->get('involved_processes');
            $robots = $request->get('involved_robots');
            $queues = $request->get('involved_queues');
            $orchestrator = $wap->client->orchestrator;

            $result = $orchestratorService->authenticate($wap->client);
            $token = null;
            if (!$result['error']) {
                $token = $result['token'];
            }

            $uiPathProcesses = array();
            foreach ($processes as $process) {
                $uiPathProcess = UiPathProcess::where('external_id', $process['external_id'])
                    ->where('ui_path_orchestrator_id', $orchestrator->id)->first();
                if (!$uiPathProcess) {
                    $process['ui_path_orchestrator_id'] = $orchestrator->id;
                    $uiPathProcess = UiPathProcess::create($process);
                }
                array_push($uiPathProcesses, $uiPathProcess->id);
            }
            $uiPathProcesses = UiPathProcess::find($uiPathProcesses);
            $wap->processes()->attach($uiPathProcesses);

            $uiPathRobots = array();
            foreach ($robots as $robot) {
                $uiPathRobot = UiPathRobot::where('external_id', $robot['external_id'])
                    ->where('ui_path_orchestrator_id', $orchestrator->id)->first();
                if (!$uiPathRobot) {
                    $robot['ui_path_orchestrator_id'] = $orchestrator->id;
                    $uiPathRobot = UiPathRobot::create($robot);

                    if ($token) {
                        $result = $orchestratorService->getSession($uiPathRobot, $token);
                        if (!$result['error']) {
                            $session = $result['session'];
                            $state = $session['State'];
                            $isUnresponsive = $session['IsUnresponsive'] ?? false;
                            $uiPathRobot->is_online = ($state === 'Available' || $state === 'Busy') && (!$isUnresponsive);

                            $until = Carbon::now();
                            $from = $until->copy()->subMinutes(15);
                            $result = $elasticSearchService->search($wap->client, "machineName: '$uiPathRobot' OR robotName: '$uiPathRobot'", $from, $until);
                            if (!$result['error']) {
                                $uiPathRobot->is_logging = $result['count'] > 0;
                            }
                            $uiPathRobot->save();
                        }
                    }
                }
                array_push($uiPathRobots, $uiPathRobot->id);
            }
            $uiPathRobots = UiPathRobot::find($uiPathRobots);
            $wap->robots()->attach($uiPathRobots);

            $uiPathQueues = array();
            foreach ($queues as $queue) {
                $uiPathQueue = UiPathQueue::where('external_id', $queue['external_id'])
                    ->where('ui_path_orchestrator_id', $orchestrator->id)->first();
                if (!$uiPathQueue) {
                    $queue['ui_path_orchestrator_id'] = $orchestrator->id;
                    $uiPathQueue = UiPathQueue::create($queue);
                }
                array_push($uiPathQueues, $uiPathQueue->id);
            }
            $uiPathQueues = UiPathQueue::find($uiPathQueues);
            $wap->queues()->attach($uiPathQueues);
        } else {
            return null;
        }
        return $wap;
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\WatchedAutomatedProcess  $watchedAutomatedProcess
     * @return \Illuminate\Http\Response
     */
    public function show(WatchedAutomatedProcess $watchedAutomatedProcess)
    {
        return $watchedAutomatedProcess;
    }
    
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\WatchedAutomatedProcess  $watchedAutomatedProcess
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, WatchedAutomatedProcess $watchedAutomatedProcess, UiPathOrchestratorService $orchestratorService)
    {
        if ($watchedAutomatedProcess->update($request->all())) {

            $timeFrom = Carbon::createFromTimeString($watchedAutomatedProcess->running_period_time_from);
            $timeUntil = Carbon::createFromTimeString($watchedAutomatedProcess->running_period_time_until);
            $runningPeriodMonday = $watchedAutomatedProcess->running_period_monday;
            $runningPeriodTuesday = $watchedAutomatedProcess->running_period_tuesday;
            $runningPeriodWednesday = $watchedAutomatedProcess->running_period_wednesday;
            $runningPeriodThursday = $watchedAutomatedProcess->running_period_thursday;
            $runningPeriodFriday = $watchedAutomatedProcess->running_period_friday;
            $runningPeriodSaturday = $watchedAutomatedProcess->running_period_saturday;
            $runningPeriodSunday = $watchedAutomatedProcess->running_period_sunday;

            $alertTriggers = AlertTrigger::all()->where('watchedAutomatedProcess', $watchedAutomatedProcess);
            foreach ($alertTriggers as $alertTrigger) {
                foreach ($alertTrigger->definitions as $definition) {
                    foreach ($definition->rules as $rule) {
                        $rule->is_triggered_on_monday = $rule->is_triggered_on_monday && $runningPeriodMonday;
                        $rule->is_triggered_on_tuesday = $rule->is_triggered_on_tuesday && $runningPeriodTuesday;
                        $rule->is_triggered_on_wednesday = $rule->is_triggered_on_wednesday && $runningPeriodWednesday;
                        $rule->is_triggered_on_thursday = $rule->is_triggered_on_thursday && $runningPeriodThursday;
                        $rule->is_triggered_on_friday = $rule->is_triggered_on_friday && $runningPeriodFriday;
                        $rule->is_triggered_on_saturday = $rule->is_triggered_on_saturday && $runningPeriodSaturday;
                        $rule->is_triggered_on_sunday = $rule->is_triggered_on_sunday && $runningPeriodSunday;
                        $timeSlotFrom = Carbon::createFromTimeString($rule->time_slot_from);
                        if ($timeFrom->gt($timeSlotFrom)) {
                            $rule->time_slot_from = $watchedAutomatedProcess->running_period_time_from;
                        }
                        $timeSlotUntil = Carbon::createFromTimeString($rule->time_slot_until);
                        if ($timeUntil->lt($timeSlotUntil)) {
                            $rule->time_slot_until = $watchedAutomatedProcess->running_period_time_until;
                        }
                        $rule->save();
                    }
                }
            }

            $processes = $request->get('involved_processes');
            $robots = $request->get('involved_robots');
            $queues = $request->get('involved_queues');
            $orchestrator = $watchedAutomatedProcess->client->orchestrator;

            $result = $orchestratorService->authenticate($watchedAutomatedProcess->client);
            $token = null;
            if (!$result['error']) {
                $token = $result['token'];
            }

            $newProcesses = array();
            $removedProcesses = array();

            // get new selected processes and attach them to the watched automated process
            foreach ($processes as $process) {
                $existingProcess = $watchedAutomatedProcess->processes->where('external_id', $process['external_id'])->first();
                if ($existingProcess === null) {
                    array_push($newProcesses, $process);
                }
            }

            // get removed processes and detach them from the watched automated process
            // and also from linked alert trigger rules
            foreach ($watchedAutomatedProcess->processes as $attachedProcess) {
                $selected = false;
                foreach ($processes as $process) {
                    if ($process['external_id'] === $attachedProcess->external_id) {
                        $selected = true;
                        break;
                    }
                }
                if (!$selected) {
                    array_push($removedProcesses, $attachedProcess);
                }
            }

            $uiPathProcesses = array();
            foreach ($newProcesses as $process) {
                $uiPathProcess = UiPathProcess::where('external_id', $process['external_id'])
                    ->where('ui_path_orchestrator_id', $orchestrator->id)->first();
                if (!$uiPathProcess) {
                    $process['ui_path_orchestrator_id'] = $orchestrator->id;
                    $uiPathProcess = UiPathProcess::create($process);
                }
                array_push($uiPathProcesses, $uiPathProcess->id);
            }
            $uiPathProcesses = UiPathProcess::find($uiPathProcesses);
            $watchedAutomatedProcess->processes()->attach($uiPathProcesses);

            if (count($removedProcesses) > 0) {
                $watchedAutomatedProcess->processes()->detach(array_column($removedProcesses, 'id'));

                foreach (AlertTrigger::all()->where('watched_automated_process_id', $watchedAutomatedProcess->id) as $trigger) {
                    foreach ($trigger->definitions as $definition) {
                        foreach ($definition->rules as $rule) {
                            $rule->processes()->detach(array_column($removedProcesses, 'id'));
                        }
                    }
                }
            }

            $newRobots = array();
            $removedRobots = array();

            // get new selected robots and attach them to the watched automated process
            foreach ($robots as $robot) {
                $existingRobot = $watchedAutomatedProcess->robots->where('external_id', $robot['external_id'])->first();
                if ($existingRobot === null) {
                    array_push($newRobots, $robot);
                }
            }

            // get removed robots and detach them from the watched automated process
            // and also from linked alert trigger rules
            foreach ($watchedAutomatedProcess->robots as $attachedRobot) {
                $selected = false;
                foreach ($robots as $robot) {
                    if ($robot['external_id'] === $attachedRobot->external_id) {
                        $selected = true;
                        break;
                    }
                }
                if (!$selected) {
                    array_push($removedRobots, $attachedRobot);
                }
            }

            $uiPathRobots = array();
            foreach ($newRobots as $robot) {
                $uiPathRobot = UiPathRobot::where('external_id', $robot['external_id'])
                    ->where('ui_path_orchestrator_id', $orchestrator->id)->first();
                if (!$uiPathRobot) {
                    $robot['ui_path_orchestrator_id'] = $orchestrator->id;
                    $uiPathRobot = UiPathRobot::create($robot);
                }
                array_push($uiPathRobots, $uiPathRobot->id);
            }
            $uiPathRobots = UiPathRobot::find($uiPathRobots);
            $watchedAutomatedProcess->robots()->attach($uiPathRobots);

            if (count($removedRobots) > 0) {
                $watchedAutomatedProcess->robots()->detach(array_column($removedRobots, 'id'));

                foreach (AlertTrigger::all()->where('watched_automated_process_id', $watchedAutomatedProcess->id) as $trigger) {
                    foreach ($trigger->definitions as $definition) {
                        foreach ($definition->rules as $rule) {
                            $rule->robots()->detach(array_column($removedRobots, 'id'));
                        }
                    }
                }
            }

            $newQueues = array();
            $removedQueues = array();

            // get new selected queues and attach them to the watched automated process
            foreach ($queues as $queue) {
                $existingQueue = $watchedAutomatedProcess->queues->where('external_id', $queue['external_id'])->first();
                if ($existingQueue === null) {
                    array_push($newQueues, $queue);
                }
            }

            // get removed queues and detach them from the watched automated process
            // and also from linked alert trigger rules
            foreach ($watchedAutomatedProcess->queues as $attachedQueue) {
                $selected = false;
                foreach ($queues as $queue) {
                    if ($queue['external_id'] === $attachedQueue->external_id) {
                        $selected = true;
                        break;
                    }
                }
                if (!$selected) {
                    array_push($removedQueues, $attachedQueue);
                }
            }

            $uiPathQueues = array();
            foreach ($newQueues as $queue) {
                $uiPathQueue = UiPathQueue::where('external_id', $queue['external_id'])
                    ->where('ui_path_orchestrator_id', $orchestrator->id)->first();
                if (!$uiPathQueue) {
                    $queue['ui_path_orchestrator_id'] = $orchestrator->id;
                    $uiPathQueue = UiPathQueue::create($queue);
                }
                array_push($uiPathQueues, $uiPathQueue->id);
            }
            $uiPathQueues = UiPathQueue::find($uiPathQueues);
            $watchedAutomatedProcess->queues()->attach($uiPathQueues);

            if (count($removedQueues) > 0) {
                $watchedAutomatedProcess->queues()->detach(array_column($removedQueues, 'id'));

                foreach (AlertTrigger::all()->where('watched_automated_process_id', $watchedAutomatedProcess->id) as $trigger) {
                    foreach ($trigger->definitions as $definition) {
                        foreach ($definition->rules as $rule) {
                            $rule->queues()->detach(array_column($removedQueues, 'id'));
                        }
                    }
                }
            }

            return $watchedAutomatedProcess;
        }
        return null;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\WatchedAutomatedProcess  $watchedAutomatedProcess
     * @return \Illuminate\Http\Response
     */
    public function destroy(WatchedAutomatedProcess $watchedAutomatedProcess)
    {
        $watchedAutomatedProcess->processes()->detach();
        $watchedAutomatedProcess->robots()->detach();
        $watchedAutomatedProcess->queues()->detach();
        
        if ($watchedAutomatedProcess->delete()) {
            return 'deleted';
        }
        return null;
    }
}
