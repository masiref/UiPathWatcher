<strong>
    Log messages count is
    @if ($rule->parameters['lowerCount'] === 0 && !$rule->parameters['higherCount'])
        equal to <span class="tag is-dark">0</span>
    @elseif ($rule->parameters['higherCount'] - $rule->parameters['lowerCount'] === 2)
        not equal to {{ $rule->parameters['higherCount'] - 1 }}
    @else
        @if ($rule->parameters['lowerCount'] >= 1)
            less than or equal to <span class="tag is-dark">{{ $rule->parameters['lowerCount'] }}</span>
        @endif
        @if ($rule->parameters['higherCount'] > 0)
            @if ($rule->parameters['lowerCount'] >= 1)
                or
            @endif
            greater than or equal to <span class="tag is-dark">{{ $rule->parameters['higherCount'] }}</span>
        @endif
    @endif
    for <span class="tag is-dark">{{ $rule->parameters['searchQuery'] }}</span>
    ElasticSearch query
</strong>