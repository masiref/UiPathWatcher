<strong>
    Log messages count for <span class="tag is-dark">{{ $rule->parameters['leftSearchQuery'] }}</span> ElasticSearch query
    is
    <span class="tag is-dark">
    @switch($rule->parameters['comparisonOperator'])
        @case('not-equal')
            not equal to
        @break
        @case('less')
            less than
        @break
        @case('less-equal')
            less than or equal to
        @break
        @case('equal')
            equal to
        @break
        @case('greater-equal')
            greater than or equal to
        @break
        @case('greater')
            greater than
        @break
    @endswitch
    </span>
    that for <span class="tag is-dark">{{ $rule->parameters['rightSearchQuery'] }}</span> ElasticSearch query
</strong>