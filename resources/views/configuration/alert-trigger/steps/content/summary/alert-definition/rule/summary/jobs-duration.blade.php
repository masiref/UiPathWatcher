<strong>
    There are jobs running for
    @if ($rule->parameters['minimalDuration'] > 0)
        less than &nbsp;<span class="tag is-dark">{{ $rule->parameters['minimalDuration'] }} minutes</span>&nbsp;
        or
    @endif
    more than &nbsp;<span class="tag is-dark">{{ $rule->parameters['maximalDuration'] }} minutes</span>&nbsp;
</strong>