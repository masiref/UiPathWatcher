<!-- failed queue items percentage -->
@include('configuration.alert-trigger.steps.content.trigger-details.alert-definition.rule.controls.failed-queue-items-percentage')
<!-- time slots -->
@include('configuration.alert-trigger.steps.content.trigger-details.alert-definition.rule.controls.time-slot')

<!-- uipath entities -->
<div class="is-divider"></div>
@include('configuration.alert-trigger.steps.content.trigger-details.alert-definition.rule.involved-entities.queues', [
    'level' => 'danger',
    'queues' => $watchedAutomatedProcess->queues
])