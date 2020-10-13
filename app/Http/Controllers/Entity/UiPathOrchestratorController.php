<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\UiPathOrchestrator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;

class UiPathOrchestratorController extends Controller
{
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //$request['api_user_password'] = Crypt::encryptString($request['api_user_password']);
        $orchestrator = UiPathOrchestrator::create($request->all());
        if ($orchestrator->save()) {
            return $orchestrator;
        }
        return null;
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\UiPathOrchestrator  $uiPathOrchestrator
     * @return \Illuminate\Http\Response
     */
    public function show(UiPathOrchestrator $uiPathOrchestrator)
    {
        return $uiPathOrchestrator;
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\UiPathOrchestrator  $uiPathOrchestrator
     * @return \Illuminate\Http\Response
     */
    public function edit(UiPathOrchestrator $uiPathOrchestrator)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\UiPathOrchestrator  $uiPathOrchestrator
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, UiPathOrchestrator $uiPathOrchestrator)
    {
        if ($uiPathOrchestrator->update($request->all())) {
            return $uiPathOrchestrator;
        }
        return null;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\UiPathOrchestrator  $uiPathOrchestrator
     * @return \Illuminate\Http\Response
     */
    public function destroy(UiPathOrchestrator $uiPathOrchestrator)
    {
        if ($uiPathOrchestrator->delete()) {
            return 'deleted';
        }
        return null;
    }
}
