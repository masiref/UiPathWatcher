<strong>
    Log messages count is
    @if ($rule->parameters['lowerCount'] > 0)
        below <span class="tag is-dark">{{ $rule->parameters['lowerCount'] }}</span>
        or
    @endif
    over <span class="tag is-dark">{{ $rule->parameters['higherCount'] }}</span>
    for <span class="tag is-dark">{{ $rule->parameters['searchQuery'] }}</span>
    ElasticSearch query
</strong>