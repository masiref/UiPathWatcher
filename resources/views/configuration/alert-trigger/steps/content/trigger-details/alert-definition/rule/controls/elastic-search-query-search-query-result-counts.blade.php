<!-- kibana search query result counts -->
<div class="field is-horizontal">
    <div class="field-body">
        <div class="field">
            <p class="control is-expanded has-icons-left has-tooltip-bottom"
                data-tooltip="Must be a positive integer value.">
                <input class="input is-danger trigger-details--alert-definition--rule--parameter
                trigger-details--alert-definition--elastic-search-query-rule--lower-count-input"
                type="text" value="{{ ($alertTriggerRule ?? false) ? $alertTriggerRule->parameters['lowerCount'] : 0 }}" placeholder="Lower count">
                <span class="icon is-small is-left">
                    <i class="fas fa-greater-than-equal"></i>
                </span>
            </p>
        </div>
        <div class="field">
            <p class="control is-expanded has-icons-left has-tooltip-bottom"
                data-tooltip="Must be a positive integer value greater than Lower count + 1.">
                <input class="input is-danger trigger-details--alert-definition--rule--parameter
                trigger-details--alert-definition--elastic-search-query-rule--higher-count-input"
                type="text" value="{{ ($alertTriggerRule ?? false) ? $alertTriggerRule->parameters['higherCount'] : '' }}" placeholder="Optional higher count">
                <span class="icon is-small is-left">
                    <i class="fas fa-less-than-equal"></i>
                </span>
            </p>
        </div>
    </div>
</div>