<?php

namespace App\Library\Services;

use App\AlertTriggerShutdown;
use App\AlertTriggerRule;
use App\Alert;
use Carbon\Carbon;

class AlertTriggerService {

    public function __construct(UiPathOrchestratorService $orchestratorService, ElasticSearchService $elasticSearchService)
    {
        $this->orchestratorService = $orchestratorService;
        $this->elasticSearchService = $elasticSearchService;
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
            } elseif ($type === 'elastic-search-multiple-queries-comparison') {
                return $this->verifyElasticSearchMultipleQueriesComparisonRule($rule, $date);
            }
        }
        return [ 'result' => false ];
    }

    protected function getToken($client)
    {
        $orchestratorService = $this->orchestratorService;
        $result = $orchestratorService->authenticate($client);
        $token = null;
        if (!$result['error']) {
            $token = $result['token'];
        }
        return $token;
    }

    protected function verifyJobsMinDurationRule(AlertTriggerRule $rule, Carbon $date)
    {
        $orchestratorService = $this->orchestratorService;

        $client = $rule->definition->trigger->watchedAutomatedProcess->client;
        $orchestrator = $client->orchestrator;
        $token = $this->getToken($client);

        $verified = false;
        $messages = array();

        foreach ($rule->robots as $robot) {
            foreach ($rule->processes as $process) {
                $filter = "Robot/Id eq {$robot->external_id} and Release/Id eq {$process->external_id} and Release/EnvironmentId eq {$process->external_environment_id}";
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
                $globalFilter = "($filter) and ($timeFilter)";

                $result = $orchestratorService->getJobs($client, $token, $globalFilter);
                if (!$result['error']) {
                    $jobs = $result['jobs'];
                    foreach ($jobs as $job) {
                        $startTime = Carbon::parse($job['StartTime']);
                        $endTime = Carbon::parse($job['EndTime']);
                        $jobKey = $job['Key'];
                        $duration = $startTime->diffInMinutes($endTime);
                        
                        // if there is at least one job with duration <= rule.duration
                        $minimalDuration = $rule->parameters['minimalDuration'];
                        if ($duration <= $minimalDuration) {
                            $verified = true;
                            if ($duration > 1) {
                                $duration = "$duration minutes";
                            } else {
                                $duration = "$duration minute";
                            }
                            if ($minimalDuration > 1) {
                                $minimalDuration = "$minimalDuration minutes";
                            } else {
                                $minimalDuration = "$minimalDuration minute";
                            }
                            array_push($messages, "Duration of job $jobKey with $process on $robot is $duration. A minimal duration of $minimalDuration is expected.");
                        }
                    }
                }
            }
        }

        return [ 'result' => $verified, 'messages' => $messages ];
    }

    protected function verifyJobsMaxDurationRule(AlertTriggerRule $rule, Carbon $date)
    {
        $orchestratorService = $this->orchestratorService;
        
        $client = $rule->definition->trigger->watchedAutomatedProcess->client;
        $orchestrator = $client->orchestrator;
        $token = $this->getToken($client);

        $verified = false;
        $messages = array();
        
        foreach ($rule->robots as $robot) {
            foreach ($rule->processes as $process) {
                $filter = "Robot/Id eq {$robot->external_id} and Release/Id eq {$process->external_id} and Release/EnvironmentId eq {$process->external_environment_id}";

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
                $globalFilter = "($filter) and ($timeFilter)";

                $result = $orchestratorService->getJobs($client, $token, $globalFilter);
                if (!$result['error']) {
                    $jobs = $result['jobs'];
                    foreach ($jobs as $job) {
                        $startTime = Carbon::parse($job['StartTime']);
                        $endTime = Carbon::parse($job['EndTime']);
                        $jobKey = $job['Key'];
                        $duration = $startTime->diffInMinutes($endTime);
                        
                        // if there is at least one job with duration >= rule.duration
                        $maximalDuration = $rule->parameters['maximalDuration'];
                        if ($duration >= $maximalDuration) {
                            $verified = true;
                            if ($duration > 1) {
                                $duration = "$duration minutes";
                            } else {
                                $duration = "$duration minute";
                            }
                            if ($maximalDuration > 1) {
                                $maximalDuration = "$maximalDuration minutes";
                            } else {
                                $maximalDuration = "$maximalDuration minute";
                            }
                            array_push($messages, "Duration of job $jobKey with $process on $robot is $duration. A maximal duration of $maximalDuration is expected.");
                            break;
                        }
                    }
                }
            }
        }
        
        return [ 'result' => $verified, 'messages' => $messages ];
    }

    protected function verifyFaultedJobsPercentageRule(AlertTriggerRule $rule, Carbon $date)
    {
        $orchestratorService = $this->orchestratorService;
        
        $client = $rule->definition->trigger->watchedAutomatedProcess->client;
        $orchestrator = $client->orchestrator;
        $token = $this->getToken($client);

        $verified = false;
        $messages = array();
        
        foreach ($rule->robots as $robot) {
            foreach ($rule->processes as $process) {
                $filter = "Robot/Id eq {$robot->external_id} and Release/Id eq {$process->external_id} and Release/EnvironmentId eq {$process->external_environment_id}";
                
                if ($rule->has_relative_time_slot) {
                    // get finished jobs with start date >= (now - relative time slot duration)
                    // get finished faulted jobs with start date >= (now - relative time slot duration)
                    $minDate = $date->copy()->subMinutes($rule->relative_time_slot_duration);
                } else {
                    // get finished jobs with start date >= today at time slot from
                    // get finished faulted jobs with start date >= today at time slot from
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
                $allJobsFilter = "($filter) and (EndTime ne null and StartTime ge {$minDate->toDateTimeLocalString()}.000Z)";
                $faultedJobsFilter = "($allJobsFilter) and State eq 'Faulted'";

                $result = $orchestratorService->getJobs($client, $token, $allJobsFilter);
                if (!$result['error']) {
                    $allJobs = $result['jobs'];

                    if (count($allJobs) > 0) {
                        $result = $orchestratorService->getJobs($client, $token, $faultedJobsFilter);
                        if (!$result['error']) {
                            $faultedJobs = $result['jobs'];
                            
                            // return jobs.faulted.count / jobs.count * 100 < rule.percentage
                            $percentage = number_format(count($faultedJobs) / count($allJobs) * 100);
                            if ($percentage >= $rule->parameters['maximalPercentage']) {
                                $verified = true;
                                array_push($messages, "Faulted jobs percentage of $process on $robot is $percentage%. A maximum of {$rule->parameters['maximalPercentage']}% is expected.");
                            }
                        }
                    }
                }
            }
        }

        return [ 'result' => $verified, 'messages' => $messages ];
    }

    protected function verifyFailedQueueItemsPercentageRule(AlertTriggerRule $rule, Carbon $date)
    {
        $orchestratorService = $this->orchestratorService;
        
        $client = $rule->definition->trigger->watchedAutomatedProcess->client;
        $orchestrator = $client->orchestrator;
        $token = $this->getToken($client);

        $verified = false;
        $messages = array();
        
        foreach ($rule->queues as $queue) {
            $filter = "QueueDefinitionId eq {$queue->external_id}";
            
            if ($rule->has_relative_time_slot) {
                // get processed queue items with start date >= (now - relative time slot duration)
                // get processed failed queue items with start date >= (now - relative time slot duration)
                $minDate = $date->copy()->subMinutes($rule->relative_time_slot_duration);
            } else {
                // get processed queue items with start date >= today at time slot from
                // get processed failed queue items with start date >= today at time slot from
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
            $allQueueItemsFilter = "($filter) and (EndTime ne null and StartTime ge {$minDate->toDateTimeLocalString()}.000Z)";
            $failedQueueItemsFilter = "($allQueueItemsFilter) and Status eq 'Failed'";

            $result = $orchestratorService->getQueueItems($client, $token, $allQueueItemsFilter);
            if (!$result['error']) {
                $allQueueItems = $result['queue-items'];

                if (count($allQueueItems) > 0) {
                    $result = $orchestratorService->getQueueItems($client, $token, $failedQueueItemsFilter);
                    if (!$result['error']) {
                        $failedQueueItems = $result['queue-items'];
                        
                        // return items.failed.count / items.count * 100 < rule.percentage
                        $percentage = number_format(count($failedQueueItems) / count($allQueueItems) * 100);
                        if ($percentage >= $rule->parameters['maximalPercentage']) {
                            $verified = true;
                            array_push($messages, "Failed queue items percentage of $queue is $percentage%. A maximum of {$rule->parameters['maximalPercentage']}% is expected.");
                        }
                    }
                }
            }
        }

        return [ 'result' => $verified, 'messages' => $messages ];
    }

    protected function verifyElasticSearchQueryRule(AlertTriggerRule $rule, Carbon $date)
    {
        $elasticSearchService = $this->elasticSearchService;

        $client = $rule->definition->trigger->watchedAutomatedProcess->client;

        $verified = false;
        $messages = array();
        
        foreach ($rule->robots as $robot) {
            foreach ($rule->processes as $process) {
                $query = "processName:'{$process->name}_{$process->environment_name}' AND (machineName:'$robot' OR robotName:'$robot')";
                
                if ($rule->has_relative_time_slot) {
                    // get processed queue items with start date >= (now - relative time slot duration)
                    // get processed failed queue items with start date >= (now - relative time slot duration)
                    $minDate = $date->copy()->subMinutes($rule->relative_time_slot_duration);
                } else {
                    // get processed queue items with start date >= today at time slot from
                    // get processed failed queue items with start date >= today at time slot from
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

                $query = "($query) AND ({$rule->parameters['searchQuery']})";
                $result = $elasticSearchService->search($client, $query, $minDate, Carbon::now());
                if (!$result['error']) {
                    $count = $result['count'];
                    if ($rule->parameters['lowerCount'] === 0 && $rule->parameters['lowerCount'] === $count && !$rule->parameters['higherCount']) {
                        $verified = true;
                        array_push($messages, "Number of messages returned for process $process on robot $robot with query {$rule->parameters['searchQuery']} is equal to 0 as expected");
                    }
                    if ($rule->parameters['lowerCount'] > 0 && $count <= $rule->parameters['lowerCount']) {
                        $verified = true;
                        array_push($messages, "Number of messages returned for process $process on robot $robot with query {$rule->parameters['searchQuery']} is less than or equal to expected value [$count <= {$rule->parameters['lowerCount']}]");
                    }
                    if ($rule->parameters['higherCount'] && $count >= $rule->parameters['higherCount']) {
                        $verified = true;
                        array_push($messages, "Number of messages returned for process $process on robot $robot with query {$rule->parameters['searchQuery']} is greater than or equal to expected value [$count >= {$rule->parameters['higherCount']}]");
                    }
                }
            }
        }
        
        return [ 'result' => $verified, 'messages' => $messages ];
    }

    protected function verifyElasticSearchMultipleQueriesComparisonRule(AlertTriggerRule $rule, Carbon $date)
    {
        $elasticSearchService = $this->elasticSearchService;

        $client = $rule->definition->trigger->watchedAutomatedProcess->client;

        $verified = false;
        $messages = array();
        
        foreach ($rule->robots as $robot) {
            foreach ($rule->processes as $process) {
                $query = "processName:'{$process->name}_{$process->environment_name}' AND (machineName:'$robot' OR robotName:'$robot')";
                
                if ($rule->has_relative_time_slot) {
                    // get processed queue items with start date >= (now - relative time slot duration)
                    // get processed failed queue items with start date >= (now - relative time slot duration)
                    $minDate = $date->copy()->subMinutes($rule->relative_time_slot_duration);
                } else {
                    // get processed queue items with start date >= today at time slot from
                    // get processed failed queue items with start date >= today at time slot from
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

                $leftQuery = "($query) AND ({$rule->parameters['leftSearchQuery']})";
                $rightQuery = "($query) AND ({$rule->parameters['rightSearchQuery']})";
                $leftResult = $elasticSearchService->search($client, $leftQuery, $minDate, Carbon::now());
                $rightResult = $elasticSearchService->search($client, $rightQuery, $minDate, Carbon::now());
                if (!$leftResult['error'] && !$rightResult['error']) {
                    $leftCount = $leftResult['count'];
                    $rightCount = $rightResult['count'];
                    $comparisonOperator = $rule->parameters['comparisonOperator'];

                    if ($comparisonOperator === 'not-equal' && leftCount != $rightCount) {
                        $verified = true;
                        array_push($messages,
                            "Number of messages returned for process $process on robot $robot with query {$rule->parameters['leftSearchQuery']} is not equal to that for query {$rule->parameters['rightSearchQuery']} as expected [$leftCount != $rightCount]");
                    } elseif ($comparisonOperator === 'less' && $leftCount < $rightCount) {
                        $verified = true;
                        array_push($messages,
                            "Number of messages returned for process $process on robot $robot with query {$rule->parameters['leftSearchQuery']} is less than that for query {$rule->parameters['rightSearchQuery']} as expected [$leftCount < $rightCount]");
                    } elseif ($comparisonOperator === 'less-equal' && $leftCount <= $rightCount) {
                        $verified = true;
                        array_push($messages,
                            "Number of messages returned for process $process on robot $robot with query {$rule->parameters['leftSearchQuery']} is less than or equal to that for query {$rule->parameters['rightSearchQuery']} as expected [$leftCount <= $rightCount]");
                    } elseif ($comparisonOperator === 'equal' && $leftCount == $rightCount) {
                        $verified = true;
                        array_push($messages,
                            "Number of messages returned for process $process on robot $robot with query {$rule->parameters['leftSearchQuery']} is equal to that for query {$rule->parameters['rightSearchQuery']} as expected [$leftCount == $rightCount]");
                    } elseif ($comparisonOperator === 'greater-equal' && $leftCount >= $rightCount) {
                        $verified = true;
                        array_push($messages,
                            "Number of messages returned for process $process on robot $robot with query {$rule->parameters['leftSearchQuery']} is greater than or equal to that for query {$rule->parameters['rightSearchQuery']} as expected [$leftCount >= $rightCount]");
                    } elseif ($comparisonOperator === 'greater' && $leftCount > $rightCount) {
                        $verified = true;
                        array_push($messages,
                            "Number of messages returned for process $process on robot $robot with query {$rule->parameters['leftSearchQuery']} is greater than that for query {$rule->parameters['rightSearchQuery']} as expected [$leftCount > $rightCount]");
                    }
                }
            }
        }
        
        return [ 'result' => $verified, 'messages' => $messages ];
    }
}