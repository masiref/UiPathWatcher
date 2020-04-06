<a class="panel-block is-active">
    <span class="panel-icon">
        <i class="fas fa-burn" aria-hidden="true"></i>
    </span>
    @include("configuration.alert-trigger.steps.content.summary.alert-definition.rule.summary.{$rule->type}")

    @include('configuration.alert-trigger.steps.content.summary.alert-definition.rule.standard-parameters.time-slot')

    @include('configuration.alert-trigger.steps.content.summary.alert-definition.rule.standard-parameters.triggering-days')

    @if ($rule->processes->count() > 0)
        @include('configuration.alert-trigger.steps.content.summary.alert-definition.rule.standard-parameters.involved-processes')
    @endif
    
    @if ($rule->robots->count() > 0)
        @include('configuration.alert-trigger.steps.content.summary.alert-definition.rule.standard-parameters.involved-robots')
    @endif
    
    @if ($rule->queues->count() > 0)
        @include('configuration.alert-trigger.steps.content.summary.alert-definition.rule.standard-parameters.involved-queues')
    @endif
</a>