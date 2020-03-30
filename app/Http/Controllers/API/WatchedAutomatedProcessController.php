<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\WatchedAutomatedProcess;
use App\UiPathProcess;
use App\UiPathRobot;
use App\UiPathQueue;
use App\Library\Services\UiPathOrchestratorService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class WatchedAutomatedProcessController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, UiPathOrchestratorService $orchestratorService)
    {
        $wap = WatchedAutomatedProcess::create($request->all());
        if ($wap->save()) {
            $processes = $request->get('involved_processes');
            $robots = $request->get('involved_robots');
            $queues = $request->get('involved_queues');
            $orchestrator = $wap->client->orchestrator;

            $result = $orchestratorService->authenticate($orchestrator);
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
     * Show the form for editing the specified resource.
     *
     * @param  \App\WatchedAutomatedProcess  $watchedAutomatedProcess
     * @return \Illuminate\Http\Response
     */
    public function edit(WatchedAutomatedProcess $watchedAutomatedProcess)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\WatchedAutomatedProcess  $watchedAutomatedProcess
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, WatchedAutomatedProcess $watchedAutomatedProcess)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\WatchedAutomatedProcess  $watchedAutomatedProcess
     * @return \Illuminate\Http\Response
     */
    public function destroy(WatchedAutomatedProcess $watchedAutomatedProcess)
    {
        //
    }
}
