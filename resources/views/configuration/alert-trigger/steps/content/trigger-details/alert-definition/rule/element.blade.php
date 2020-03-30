@php
    $title = "Rule n°<span class='trigger-details--alert-rule--rank'>{$alertTriggerRule->rank}</span>";
@endphp

@component('configuration.alert-trigger.steps.content.trigger-details.alert-definition.rule.component', [
    'title' => $title,
    'type' => $alertTriggerRule->type,
    'rank' => $alertTriggerRule->rank
])

    @if ($alertTriggerRule->type !== 'none')
        @include("configuration.alert-trigger.steps.content.trigger-details.alert-definition.rule.{$alertTriggerRule->type}")
    @endif

@endcomponent