<strong>
    Log messages count is
    @if ($rule->parameters['lowerCount'] > 0)
        below &nbsp;<span class="tag is-dark">{{ $rule->parameters['lowerCount'] }}</span>&nbsp;
        or
    @endif
    over &nbsp;<span class="tag is-dark">{{ $rule->parameters['higherCount'] }}</span>&nbsp;
    for &nbsp;<span class="tag is-dark">{{ $rule->parameters['searchQuery'] }}</span>&nbsp;
    ElasticSearch query
</strong>