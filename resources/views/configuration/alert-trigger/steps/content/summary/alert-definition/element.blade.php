<article class="panel is-{{ $definition->level }}">
    <p class="panel-heading has-text-centered">
        <span class="icon"><i class="fas fa-burn"></i></span>
        <span>Alert definition {{ $definition->id ? '#' . str_pad($definition->id, 4, '0', STR_PAD_LEFT) : 'n°' . $definition->rank }}</span>
    </p>
    @foreach ($definition->rules as $rule)
        @include('configuration.alert-trigger.steps.content.summary.alert-definition.rule.element')
    @endforeach
</article>