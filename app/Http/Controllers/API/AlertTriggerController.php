<?php

namespace App\Http\Controllers\API;

use App\AlertTrigger;
use App\AlertTriggerDefinition;
use App\AlertTriggerRule;
use App\UiPathProcess;
use App\UiPathRobot;
use App\UiPathQueue;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AlertTriggerController extends Controller
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
        $alertTrigger = AlertTrigger::create([
            'title' => $request->get('title'),
            'watched_automated_process_id' => $request->get('watched_automated_process_id')
        ]);
        if ($alertTrigger) {
            $definitions = $request->get('definitions');
            foreach ($definitions as $definition) {
                $alertTriggerDefinition = AlertTriggerDefinition::create([
                    'alert_trigger_id' => $alertTrigger->id,
                    'level' => $definition['level'],
                    'rank' => $definition['rank']
                ]);
                $rules = $definition['rules'];
                foreach ($rules as $rule) {
                    $alertTriggerRule = AlertTriggerRule::create([
                        'alert_trigger_definition_id' => $alertTriggerDefinition->id,
                        'type' => $rule['type'],
                        'rank' => $rule['rank'],
                        'time_slot_from' => $rule['time_slot_from'],
                        'time_slot_until' => $rule['time_slot_until'],
                        'has_relative_time_slot' => $rule['has_relative_time_slot'],
                        'relative_time_slot_duration' => $rule['relative_time_slot_duration'],
                        'is_triggered_on_monday' => $rule['is_triggered_on_monday'],
                        'is_triggered_on_tuesday' => $rule['is_triggered_on_tuesday'],
                        'is_triggered_on_wednesday' => $rule['is_triggered_on_wednesday'],
                        'is_triggered_on_thursday' => $rule['is_triggered_on_thursday'],
                        'is_triggered_on_friday' => $rule['is_triggered_on_friday'],
                        'is_triggered_on_saturday' => $rule['is_triggered_on_saturday'],
                        'is_triggered_on_sunday' => $rule['is_triggered_on_sunday'],
                        'parameters' => $rule['parameters']
                    ]);
                    $uiPathProcesses = UiPathProcess::find($rule['processes']);
                    $alertTriggerRule->processes()->attach($uiPathProcesses);
                    $uiPathRobots = UiPathRobot::find($rule['robots']);
                    $alertTriggerRule->robots()->attach($uiPathRobots);
                    $uiPathQueues = UiPathQueue::find($rule['queues']);
                    $alertTriggerRule->queues()->attach($uiPathQueues);
                }
            }
            return AlertTrigger::find($alertTrigger->id);
        }
        return null;
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\AlertTrigger  $alertTrigger
     * @return \Illuminate\Http\Response
     */
    public function show(AlertTrigger $alertTrigger)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\AlertTrigger  $alertTrigger
     * @return \Illuminate\Http\Response
     */
    public function edit(AlertTrigger $alertTrigger)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\AlertTrigger  $alertTrigger
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, AlertTrigger $alertTrigger)
    {
        $alertTrigger->fill($request->all());
        if ($alertTrigger->save()) {
            return $alertTrigger;
        }
        return null;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\AlertTrigger  $alertTrigger
     * @return \Illuminate\Http\Response
     */
    public function destroy(AlertTrigger $alertTrigger)
    {
        //
    }
}
