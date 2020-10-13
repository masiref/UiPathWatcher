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
use Carbon\Carbon;

class AlertTriggerController extends Controller
{
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
                $this->createAlertTriggerDefinition($alertTrigger, $definition);
            }
            return AlertTrigger::find($alertTrigger->id);
        }
        return null;
    }

    protected function createAlertTriggerDefinition($alertTrigger, $data)
    {
        $alertTriggerDefinition = AlertTriggerDefinition::create([
            'alert_trigger_id' => $alertTrigger->id,
            'level' => $data['level'],
            'rank' => $data['rank']
        ]);
        $rules = $data['rules'];
        foreach ($rules as $rule) {
            $alertTriggerRule = $this->createAlertTriggerRule($alertTriggerDefinition, $rule);
        }
        return AlertTriggerDefinition::find($alertTriggerDefinition->id);
    }

    protected function updateAlertTriggerDefinition($data)
    {
        $alertTriggerDefinition = AlertTriggerDefinition::find($data['id']);
        $alertTriggerDefinition->update([
            'level' => $data['level'],
            'rank' => $data['rank']
        ]);

        $newRules = array();
        $removeRulesIds = array();
        $existingRules = array();

        foreach ($data['rules'] as $rule) {
            // get new rules
            if (!$rule['id']) {
                array_push($newRules, $rule);
            } else {
                // get existing rules
                if ($alertTriggerDefinition->rules->pluck('id')->contains($rule['id'])) {
                    array_push($existingRules, $rule);
                }
            }
        }
        // get removed rules
        foreach ($alertTriggerDefinition->rules as $rule) {
            if (!in_array($rule->id, array_column($data['rules'], 'id'))) {
                array_push($removeRulesIds, $rule->id);
            }
        }

        foreach ($newRules as $rule) {
            $this->createAlertTriggerRule($alertTriggerDefinition, $rule);
        }

        foreach ($removeRulesIds as $id) {
            $rule = AlertTriggerRule::find($id);
            if (!$rule->deleted) {
                $rule->update([
                    'rank' => 0,
                    'deleted' => true,
                    'deleted_at' => Carbon::now()
                ]);
            }
        }

        foreach ($existingRules as $rule) {
            $this->updateAlertTriggerRule($rule);
        }

        return AlertTriggerDefinition::find($alertTriggerDefinition->id);
    }

    public function createAlertTriggerRule($alertTriggerDefinition, $data)
    {
        $standardParameters = $data['parameters']['standard'];
        $triggeringDays = $standardParameters['triggeringDays'];
        $specificParameters = $data['parameters']['specific'];
        $hasRelativeTimeSlot = array_key_exists('relativeTimeSlot', $standardParameters)
            && $standardParameters['relativeTimeSlot'] !== null;

        $alertTriggerRule = AlertTriggerRule::create([
            'alert_trigger_definition_id' => $alertTriggerDefinition->id,
            'type' => $data['type'],
            'rank' => $data['rank'],
            'time_slot_from' => $standardParameters['timeSlot']['from'],
            'time_slot_until' => $standardParameters['timeSlot']['to'],
            'has_relative_time_slot' => $hasRelativeTimeSlot,
            'relative_time_slot_duration' => $hasRelativeTimeSlot ? $standardParameters['relativeTimeSlot'] : null,
            'is_triggered_on_monday' => $triggeringDays['monday'],
            'is_triggered_on_tuesday' => $triggeringDays['tuesday'],
            'is_triggered_on_wednesday' => $triggeringDays['wednesday'],
            'is_triggered_on_thursday' => $triggeringDays['thursday'],
            'is_triggered_on_friday' => $triggeringDays['friday'],
            'is_triggered_on_saturday' => $triggeringDays['saturday'],
            'is_triggered_on_sunday' => $triggeringDays['sunday'],
            'parameters' => $specificParameters
        ]);
            
        $involvedEntities = $standardParameters['involvedEntities'];

        $processes = array_key_exists('processes', $involvedEntities) ? $involvedEntities['processes'] : array();
        $uiPathProcesses = UiPathProcess::find($processes);
        $alertTriggerRule->processes()->attach($uiPathProcesses);

        $robots = array_key_exists('robots', $involvedEntities) ? $involvedEntities['robots'] : array();
        $uiPathRobots = UiPathRobot::find($robots);
        $alertTriggerRule->robots()->attach($uiPathRobots);

        $queues = array_key_exists('queues', $involvedEntities) ? $involvedEntities['queues'] : array();
        $uiPathQueues = UiPathQueue::find($queues);
        $alertTriggerRule->queues()->attach($uiPathQueues);
        
        return $alertTriggerRule;
    }

    public function updateAlertTriggerRule($data)
    {
        $standardParameters = $data['parameters']['standard'];
        $triggeringDays = $standardParameters['triggeringDays'];
        $specificParameters = $data['parameters']['specific'];
        $hasRelativeTimeSlot = array_key_exists('relativeTimeSlot', $standardParameters)
            && $standardParameters['relativeTimeSlot'] !== null;

        $alertTriggerRule = AlertTriggerRule::find($data['id']);
        $alertTriggerRule->update([
            'type' => $data['type'],
            'rank' => $data['rank'],
            'time_slot_from' => $standardParameters['timeSlot']['from'],
            'time_slot_until' => $standardParameters['timeSlot']['to'],
            'has_relative_time_slot' => $hasRelativeTimeSlot,
            'relative_time_slot_duration' => $hasRelativeTimeSlot ? $standardParameters['relativeTimeSlot'] : null,
            'is_triggered_on_monday' => $triggeringDays['monday'],
            'is_triggered_on_tuesday' => $triggeringDays['tuesday'],
            'is_triggered_on_wednesday' => $triggeringDays['wednesday'],
            'is_triggered_on_thursday' => $triggeringDays['thursday'],
            'is_triggered_on_friday' => $triggeringDays['friday'],
            'is_triggered_on_saturday' => $triggeringDays['saturday'],
            'is_triggered_on_sunday' => $triggeringDays['sunday'],
            'parameters' => $specificParameters
        ]);
            
        $involvedEntities = $standardParameters['involvedEntities'];

        $processes = array_key_exists('processes', $involvedEntities) ? $involvedEntities['processes'] : array();
        $uiPathProcesses = UiPathProcess::find($processes);
        $alertTriggerRule->processes()->sync($uiPathProcesses->pluck('id'));

        $robots = array_key_exists('robots', $involvedEntities) ? $involvedEntities['robots'] : array();
        $uiPathRobots = UiPathRobot::find($robots);
        $alertTriggerRule->robots()->sync($uiPathRobots->pluck('id'));

        $queues = array_key_exists('queues', $involvedEntities) ? $involvedEntities['queues'] : array();
        $uiPathQueues = UiPathQueue::find($queues);
        $alertTriggerRule->queues()->sync($uiPathQueues->pluck('id'));
        
        return $alertTriggerRule;
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\AlertTrigger  $alertTrigger
     * @return \Illuminate\Http\Response
     */
    public function show(AlertTrigger $alertTrigger)
    {
        $alertTrigger->load('definitions.rules.processes');
        $alertTrigger->load('definitions.rules.robots');
        $alertTrigger->load('definitions.rules.queues');
        return $alertTrigger;
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
        if ($request->get('title')) {
            $alertTrigger->update([
                'title' => $request->get('title')
            ]);
        }
        if ($request->get('active')) {
            $alertTrigger->update([
                'active' => $request->get('active')
            ]);
        }

        if ($request->get('definitions')) {
            $definitions = $request->get('definitions');

            $newDefinitions = array();
            $removedDefinitionsIds = array();
            $existingDefinitions = array();

            foreach ($definitions as $definition) {

                // get new definitions
                if (!$definition['id']) {
                    array_push($newDefinitions, $definition);
                } else {
                    // get existing definitions
                    if ($alertTrigger->definitions->pluck('id')->contains($definition['id'])) {
                        array_push($existingDefinitions, $definition);
                    }
                }
            }
            // get removed definitions
            foreach($alertTrigger->definitions as $definition) {
                if (!in_array($definition->id, array_column($definitions, 'id'))) {
                    array_push($removedDefinitionsIds, $definition['id']);
                }
            }

            foreach ($newDefinitions as $definition) {
                $alertTriggerDefinition = $this->createAlertTriggerDefinition($alertTrigger, $definition);
            }

            foreach ($removedDefinitionsIds as $id) {
                $definition = AlertTriggerDefinition::find($id);
                if (!$definition->deleted) {
                    $definition->update([
                        'rank' => 0,
                        'deleted' => true,
                        'deleted_at' => Carbon::now()
                    ]);
                }
            }

            foreach ($existingDefinitions as $definition) {
                $alertTriggerDefinition = $this->updateAlertTriggerDefinition($definition);
            }
        }

        $alertTrigger = AlertTrigger::find($alertTrigger->id);
        $alertTrigger->load('definitions.rules.processes');
        $alertTrigger->load('definitions.rules.robots');
        $alertTrigger->load('definitions.rules.queues');

        return $alertTrigger;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\AlertTrigger  $alertTrigger
     * @return \Illuminate\Http\Response
     */
    public function destroy(AlertTrigger $alertTrigger)
    {
        $alertTrigger->setDeleted();
        return 'deleted';
    }
}
