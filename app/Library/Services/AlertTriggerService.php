<?php

namespace App\Library\Services;

use App\AlertTriggerShutdown;
use App\AlertTriggerRule;
use App\Alert;
use Carbon\Carbon;

class AlertTriggerService {

    public function __construct(UiPathOrchestratorService $orchestratorService)
    {
        $this->orchestratorService = $orchestratorService;
    }

    public function isUnderShutdown()
    {
        return AlertTriggerShutdown::where('ended_at', null)->count() > 0;
    }

    public function currentShutdown()
    {
        return AlertTriggerShutdown::where('ended_at', null)->first();
    }

    public function verifyRule(AlertTriggerRule $rule, Carbon $date)
    {
        if ($rule->triggeredOnDate($date)) {
            $type = $rule->type;
            if ($type === 'jobs-min-duration') {
                return $this->verifyJobsMinDurationRule($rule, $date);
            } elseif ($type === 'jobs-max-duration') {
                return $this->verifyJobsMaxDurationRule($rule, $date);
            } elseif ($type === 'faulted-jobs-percentage') {
                return $this->verifyFaultedJobsPercentageRule($rule, $date);
            } elseif ($type === 'failed-queue-items-percentage') {
                return $this->verifyFailedQueueItemsPercentageRule($rule, $date);
            } elseif ($type === 'elastic-search-query') {
                return $this->verifyElasticSearchQueryRule($rule, $date);
            }
        }
        return false;
    }

    protected function getToken($orchestrator)
    {
        $orchestratorService = $this->orchestratorService;
        $result = $orchestratorService->authenticate($orchestrator);
        $token = null;
        if (!$result['error']) {
            $token = $result['token'];
        }
        return $token;
    }

    protected function verifyJobsMinDurationRule(AlertTriggerRule $rule, Carbon $date)
    {
        $orchestratorService = $this->orchestratorService;

        $orchestrator = $rule->definition->trigger->watchedAutomatedProcess->client->orchestrator;
        $token = $this->getToken($orchestrator);

        // for jobs on rule.robots executing rule.processes
        $filter = $this->orchestratorRobotsProcessesFilter($rule);

        if ($rule->has_relative_time_slot) {
            // get finished jobs with start date >= (now - relative time slot duration)
            $minDate = $date->copy()->subMinutes($rule->relative_time_slot_duration);
        } else {
            // get finished jobs with start date >= today at time slot from
            $minDate = Carbon::createFromTimeString($rule->time_slot_from);

            // set to closed date of last closed alert triggered by same definition
            // to avoid triggering alerts already triggered
            $lastClosedAlert = Alert::all()->where('closed', true)->where('parent', null)
                ->where('alert_trigger_definition_id', $rule->definition->id)
                ->sortByDesc('closed_at')->first();
            if ($lastClosedAlert) {
                $lastClosedAlertClosedDate = Carbon::parse($lastClosedAlert->closed_at);
                if ($lastClosedAlertClosedDate->greaterThan($minDate)) {
                    $minDate = $lastClosedAlertClosedDate;
                }                
            }
        }

        $minDate->tz('UTC');
        $timeFilter = "EndTime ne null and StartTime ge {$minDate->toDateTimeLocalString()}.000Z";
        $globalFilter = "$filter and ($timeFilter)";

        $result = $orchestratorService->getJobs($orchestrator, $token, $globalFilter);
        if (!$result['error']) {
            $jobs = $result['jobs'];
            foreach ($jobs as $job) {
                $startTime = Carbon::parse($job['StartTime']);
                $endTime = Carbon::parse($job['EndTime']);
                $duration = $startTime->diffInMinutes($endTime);
                
                // if there is at least one job with duration <= rule.duration
                if ($duration <= $rule->parameters['minimalDuration']) {
                    return true;
                }
            }
        }
        return false;
    }

    protected function verifyJobsMaxDurationRule(AlertTriggerRule $rule, Carbon $date)
    {
        $orchestratorService = $this->orchestratorService;
        
        $orchestrator = $rule->definition->trigger->watchedAutomatedProcess->client->orchestrator;
        $token = $this->getToken($orchestrator);

        // for jobs on rule.robots executing rule.processes
        $filter = $this->orchestratorRobotsProcessesFilter($rule);

        // get not finished jobs with start date >= today at time slot from
        $minDate = Carbon::createFromTimeString($rule->time_slot_from);
        // set to closed date of last closed alert triggered by same definition
        // to avoid triggering alerts already triggered
        $lastClosedAlert = Alert::all()->where('closed', true)->where('parent', null)
            ->where('alert_trigger_definition_id', $rule->definition->id)
            ->sortByDesc('closed_at')->first();
        if ($lastClosedAlert) {
            $lastClosedAlertClosedDate = Carbon::parse($lastClosedAlert->closed_at);
            if ($lastClosedAlertClosedDate->greaterThan($minDate)) {
                $minDate = $lastClosedAlertClosedDate;
            }                
        }
        $minDate->tz('UTC');
        $timeFilter = "StartTime ge {$minDate->toDateTimeLocalString()}.000Z";
        $globalFilter = "$filter and ($timeFilter)";

        $result = $orchestratorService->getJobs($orchestrator, $token, $globalFilter);
        if (!$result['error']) {
            $jobs = $result['jobs'];
            foreach ($jobs as $job) {
                $startTime = Carbon::parse($job['StartTime']);
                $endTime = Carbon::parse($job['EndTime']);
                $duration = $startTime->diffInMinutes($endTime);
                
                // if there is at least one job with duration >= rule.duration
                if ($duration >= $rule->parameters['maximalDuration']) {
                    return true;
                }
            }
        }

        return false;
    }

    protected function verifyFaultedJobsPercentageRule(AlertTriggerRule $rule, Carbon $date)
    {
        $orchestratorService = $this->orchestratorService;
        
        $orchestrator = $rule->definition->trigger->watchedAutomatedProcess->client->orchestrator;
        $token = $this->getToken($orchestrator);

        // for jobs on rule.robots executing rule.processes
        $filter = $this->orchestratorRobotsProcessesFilter($rule);
        
        if ($rule->has_relative_time_slot) {
            // get finished jobs with start date >= (now - relative time slot duration)
            // get finished faulted jobs with start date >= (now - relative time slot duration)
            $minDate = $date->copy()->subMinutes($rule->relative_time_slot_duration);
        } else {
            // get finished jobs with start date >= today at time slot from
            // get finished faulted jobs with start date >= today at time slot from
            $minDate = Carbon::createFromTimeString($rule->time_slot_from);
        }
        $allJobsFilter = "EndTime ne null and StartTime ge {$minDate->toDateTimeLocalString()}.000Z";
        $faultedJobsFilter = "$allJobsFilter and State eq 'Faulted'";

        $result = $orchestratorService->getJobs($orchestrator, $token, $allJobsFilter);
        if (!$result['error']) {
            $allJobs = $result['jobs'];

            if (count($allJobs) > 0) {
                $result = $orchestratorService->getJobs($orchestrator, $token, $faultedJobsFilter);
                if (!$result['error']) {
                    $faultedJobs = $result['jobs'];
                    
                    // return jobs.faulted.count / jobs.count * 100 < rule.percentage
                    return count($faultedJobs) / count($allJobs) * 100 >= $rule->parameters['maximalPercentage'];
                }
            }
        }
        return false;
    }

    protected function verifyFailedQueueItemsPercentageRule(AlertTriggerRule $rule, Carbon $date)
    {
        // for queue items of rule.queue

        // if rule has relative time slot duration
        //     get queue items with start date >= (now - relative time slot duration) and end date not empty
        // else
        //     get queue items with start date >= today at time slot from and end date not empty
        // return items.failed.count / items.count * 100 < rule.percentage
        return false;
    }

    protected function verifyElasticSearchQueryRule(AlertTriggerRule $rule, Carbon $date)
    {
        // searching on rule.robots + rule.processes

        // return search count > rule.min count or < rule.max count
        return false;
    }

    protected function orchestratorRobotsProcessesFilter(AlertTriggerRule $rule)
    {
        $robotsFilter = $this->orchestratorRobotsFilter($rule->robots);
        $processesFilter = $this->orchestratorProcessesFilter($rule->processes);

        $filter = $robotsFilter;
        $filter = $filter === '' ? $processesFilter : "$filter and $processesFilter";

        return $filter;
    }

    protected function orchestratorRobotsFilter($robots)
    {
        $robotsFilter = '';
        foreach ($robots as $robot) {
            $robotsFilter .=
                ($robotsFilter === '' ? '' : ' or ')
                . "Robot/Id eq {$robot->external_id}";
        }
        $robotsFilter = $robotsFilter !== '' ? "($robotsFilter)" : '';

        return $robotsFilter;
    }

    protected function orchestratorProcessesFilter($processes)
    {
        $processesFilter = '';
        foreach ($processes as $process) {
            $processesFilter .= 
                ($processesFilter === '' ? '' : ' or ')
                . "(Release/Id eq {$process->external_id} and Release/EnvironmentId eq {$process->external_environment_id})";
        }
        $processesFilter = $processesFilter !== '' ? "($processesFilter)" : '';

        return $processesFilter;
    }
}