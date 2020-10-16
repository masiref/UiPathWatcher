<a class="panel-block is-active">
    <span class="panel-icon">
        <i class="fas fa-swatchbook" aria-hidden="true"></i>
    </span>
    @include("configuration.alert-trigger.steps.content.summary.alert-definition.rule.{$rule->type}")

    @include('configuration.alert-trigger.steps.content.summary.alert-definition.rule.time-slot')

    @include('configuration.alert-trigger.steps.content.summary.alert-definition.rule.triggering-days')

    @if ($rule->processes->count() > 0)
        @include('configuration.alert-trigger.steps.content.summary.alert-definition.rule.involved-entities.processes')
    @endif
    
    @if ($rule->robots->count() > 0)
        @include('configuration.alert-trigger.steps.content.summary.alert-definition.rule.involved-entities.robots')
    @endif
    
    @if ($rule->queues->count() > 0)
        @include('configuration.alert-trigger.steps.content.summary.alert-definition.rule.involved-entities.queues')
    @endif
</a>