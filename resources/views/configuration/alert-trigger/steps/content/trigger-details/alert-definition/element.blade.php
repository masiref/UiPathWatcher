@php
    $title = 'Alert definition ';
    if ($alertTriggerDefinition && $alertTriggerDefinition->id) {
        $title .= '#' . str_pad($alertTriggerDefinition->id, 4, '0', STR_PAD_LEFT);
    } else {
        $title .= "n°<span class='trigger-details--alert-definition--rank'>{$alertTriggerDefinition->rank}</span>";
    }
@endphp

@component('configuration.alert-trigger.steps.content.trigger-details.alert-definition.component', [
    'title' => $title,
    'level' => $alertTriggerDefinition->level,
    'rank' => $alertTriggerDefinition->rank,
    'valid' => $alertTriggerDefinition->id !== null
])
    @foreach ($alertTriggerDefinition->rules->where('deleted', false) as $alertTriggerRule)
        @include('configuration.alert-trigger.steps.content.trigger-details.alert-definition.rule.element', [
            'watchedAutomatedProcess' => $alertTrigger->watchedAutomatedProcess
        ])
    @endforeach
@endcomponent