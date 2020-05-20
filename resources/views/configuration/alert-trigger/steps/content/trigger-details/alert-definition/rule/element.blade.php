@php
    $title = 'Rule ';
    if ($alertTriggerRule && $alertTriggerRule->id) {
        $title .= '#' . str_pad($alertTriggerRule->id, 4, '0', STR_PAD_LEFT);
    } else {
        $title .= "n°<span class='trigger-details--alert-rule--rank'>{$alertTriggerRule->rank}</span>";
    }
@endphp

@component('configuration.alert-trigger.steps.content.trigger-details.alert-definition.rule.component', [
    'title' => $title,
    'type' => $alertTriggerRule->type,
    'rank' => $alertTriggerRule->rank,
    'valid' => $alertTriggerRule->id !== null
])

    @if ($alertTriggerRule->type !== 'none')
        @include("configuration.alert-trigger.steps.content.trigger-details.alert-definition.rule.{$alertTriggerRule->type}")
    @endif

@endcomponent