<!-- ElasticSearch search query result counts -->
@php
    $comparisonOperator = null;
    if ($alertTriggerRule ?? false) {
        $comparisonOperator = $alertTriggerRule->parameters['comparisonOperator'];
    }
    if (!$comparisonOperator) {
        $comparisonOperator = 'none';
    }
@endphp
<div class="field is-horizontal">
    <div class="field-body">
        <div class="field">
            <p class="control is-expanded has-icons-left">
                <input class="input" type="text" value="Left query results count" readonly>
                <span class="icon is-small is-left">
                    <i class="fas fa-caret-square-left"></i>
                </span>
            </p>
        </div>
        <div class="field">
            <div class="control has-icons-left">
                <div class="select is-fullwidth is-{{ $comparisonOperator === 'none' ? 'danger' : 'success' }}">
                    <select class="trigger-details--alert-definition--rule--parameter
                        trigger-details--alert-definition--elastic-search-multiple-queries-comparison-rule--comparison-operator-input">
                        <option value="none" {!! $comparisonOperator === 'none' ? 'selected' : '' !!}>Select a comparison operator</option>
                        <option value="not-equal" {!! $comparisonOperator === 'not-equal' ? 'selected' : '' !!}>is not equal to</option>
                        <option value="less" {!! $comparisonOperator === 'less' ? 'selected' : '' !!}>is less than</option>
                        <option value="less-equal" {!! $comparisonOperator === 'less-equal' ? 'selected' : '' !!}>is less than or equal to</option>
                        <option value="equal" {!! $comparisonOperator === 'equal' ? 'selected' : '' !!}>is equal to</option>
                        <option value="greater-equal" {!! $comparisonOperator === 'greater-equal' ? 'selected' : '' !!}>is greater than or equal to</option>
                        <option value="greater" {!! $comparisonOperator === 'greater' ? 'selected' : '' !!}>is greater than</option>
                    </select>
                </div>
                <span class="icon is-small is-left">
                    <i class="fas fa-balance-scale-left"></i>
                </span>
            </div>
        </div>
        <div class="field">
            <p class="control is-expanded has-icons-right">
                <input class="input" type="text" value="Right query results count" readonly>
                <span class="icon is-small is-right">
                    <i class="fas fa-caret-square-right"></i>
                </span>
            </p>
        </div>
    </div>
</div>