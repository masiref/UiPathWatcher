<!-- kibana search query -->
@include('configuration.alert-trigger.steps.content.trigger-details.alert-definition.rule.controls.elastic-search-query-search-query')
<!-- kibana search query result counts -->
@include('configuration.alert-trigger.steps.content.trigger-details.alert-definition.rule.controls.elastic-search-query-search-query-result-counts')
<!-- time slots -->
@include('configuration.alert-trigger.steps.content.trigger-details.alert-definition.rule.controls.time-slot')

<div class="is-divider"></div>
@include('configuration.alert-trigger.steps.content.trigger-details.alert-definition.rule.controls.triggering-days', [
    'level' => 'danger'
])

<!-- uipath entities -->
<div class="is-divider"></div>
@include('configuration.alert-trigger.steps.content.trigger-details.alert-definition.rule.involved-entities.processes', [
    'level' => 'danger',
    'processes' => $watchedAutomatedProcess->processes
])
@include('configuration.alert-trigger.steps.content.trigger-details.alert-definition.rule.involved-entities.robots', [
    'level' => 'danger',
    'robots' => $watchedAutomatedProcess->robots
])