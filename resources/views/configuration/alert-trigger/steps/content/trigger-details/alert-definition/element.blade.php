@php
    $title = "Alert definition n°<span class='trigger-details--alert-definition--rank'>{$alertTriggerDefinition->rank}</span>";
@endphp

@component('configuration.alert-trigger.steps.content.trigger-details.alert-definition.component', [
    'title' => $title,
    'level' => $alertTriggerDefinition->level,
    'rank' => $alertTriggerDefinition->rank,
    'valid' => $alertTriggerDefinition->id !== null
])
    {{-- rules --}}
@endcomponent