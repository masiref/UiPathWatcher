<div class="field is-horizontal">
    <div class="field-body">
        <div class="field">
            <p class="control is-expanded has-icons-left has-tooltip-bottom"
                data-tooltip="Must be a string validating Lucene syntax.">
                <input class="input is-danger trigger-details--alert-definition--rule--parameter
                trigger-details--alert-definition--elastic-search-multiple-queries-comparison-rule--left-search-query-input"
                type="text" value="{{ ($alertTriggerRule ?? false) ? $alertTriggerRule->parameters['leftSearchQuery'] : '' }}"
                placeholder="Left search query">
                <span class="icon is-small is-left">
                    <i class="fas fa-search"></i>
                </span>
            </p>
        </div>
    </div>
</div>
<div class="field is-horizontal">
    <div class="field-body">
        <div class="field">
            <p class="control is-expanded has-icons-left has-tooltip-bottom"
                data-tooltip="Must be a string validating Lucene syntax.">
                <input class="input is-danger trigger-details--alert-definition--rule--parameter
                trigger-details--alert-definition--elastic-search-multiple-queries-comparison-rule--right-search-query-input"
                type="text" value="{{ ($alertTriggerRule ?? false) ? $alertTriggerRule->parameters['rightSearchQuery'] : '' }}"
                placeholder="Right search query">
                <span class="icon is-small is-left">
                    <i class="fas fa-search"></i>
                </span>
            </p>
        </div>
    </div>
</div>