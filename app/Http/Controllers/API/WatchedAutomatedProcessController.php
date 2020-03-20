<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\WatchedAutomatedProcess;
use App\UiPathProcess;
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
    public function store(Request $request)
    {
        $wap = WatchedAutomatedProcess::create($request->all());
        if ($wap->save()) {
            $processes = $request->get('involved_processes');
            $orchestrator = $wap->client->orchestrator;
            $uiPathProcesses = array();
            foreach ($processes as $process) {
                $uiPathProcess = UiPathProcess::where('external_id', $process['external_id'])->where('ui_path_orchestrator_id', $orchestrator->id)->first();
                if (!$uiPathProcess) {
                    $process['ui_path_orchestrator_id'] = $orchestrator->id;
                    $uiPathProcess = UiPathProcess::create($process);
                }
                array_push($uiPathProcesses, $uiPathProcess->id);
            }
            $uiPathProcesses = UiPathProcess::find($uiPathProcesses);
            $wap->processes()->attach($uiPathProcesses);
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
