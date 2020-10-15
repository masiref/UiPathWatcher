<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Library\Services\UiPathOrchestratorService;
use App\Library\Services\ElasticSearchService;
use App\UiPathRobot;
use Carbon\Carbon;

class UpdateUiPathRobotsStatuses implements ShouldQueue
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
    public function handle(UiPathOrchestratorService $orchestratorService, ElasticSearchService $elasticSearchService)
    {
        $robots = UiPathRobot::all();

        foreach ($robots as $robot) {
            $wap = $robot->watchedAutomatedProcesses->first();
            if ($wap) {
                $client = $wap->client;
                $result = $orchestratorService->authenticate($client);
                $token = null;
                if (!$result['error']) {
                    $token = $result['token'];
                }
                if ($token) {
                    $result = $orchestratorService->getSession($robot, $token);
                    if (!$result['error']) {
                        $session = $result['session'];
                        $state = $session['State'];
                        $isUnresponsive = $session['IsUnresponsive'] ?? false;
                        $robot->is_online = ($state === 'Available' || $state === 'Busy') && (!$isUnresponsive);
                    }
                }

                $until = Carbon::now();
                $from = $until->copy()->subMinutes(15);
                $result = $elasticSearchService->search($client, "machineName: '$robot' OR robotName: '$robot'", $from, $until);
                if (!$result['error']) {
                    $robot->is_logging = $result['count'] > 0;
                }
                $robot->save();
            }
        }
    }
}
