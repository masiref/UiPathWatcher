<!-- ElasticSearch search queries -->
@include('configuration.alert-trigger.steps.content.trigger-details.alert-definition.rule.controls.elastic-search-multiple-queries-comparison-search-queries')
<!-- ElasticSearch search multiple queries result counts -->
@include('configuration.alert-trigger.steps.content.trigger-details.alert-definition.rule.controls.elastic-search-multiple-queries-comparison-results-count')
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