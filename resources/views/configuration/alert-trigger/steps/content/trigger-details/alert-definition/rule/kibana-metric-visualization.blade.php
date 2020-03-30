<!-- kibana metric visualization selection -->
@include('configuration.alert-trigger.steps.content.trigger-details.alert-definition.rule.controls.kibana-metric-visualization-selection')
<!-- kibana metric visualization result counts -->
@include('configuration.alert-trigger.steps.content.trigger-details.alert-definition.rule.controls.kibana-metric-visualization-result-counts')
<!-- time slots -->
@include('configuration.alert-trigger.steps.content.trigger-details.alert-definition.rule.controls.time-slot')

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