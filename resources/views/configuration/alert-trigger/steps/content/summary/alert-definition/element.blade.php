<article class="panel is-{{ $definition->level }}">
    <p class="panel-heading">
        Alert definition n°{{ $definition->rank }}
    </p>
    @foreach ($definition->rules as $rule)
        @include('configuration.alert-trigger.steps.content.summary.alert-definition.rule.element')
    @endforeach
</article>