<!-- ElasticSearch search query result counts -->
<div class="field is-horizontal">
    <div class="field-body">
        <div class="field">
            <p class="control is-expanded has-icons-left has-tooltip-bottom"
                data-tooltip="Must be an integer value greater than or equal to zero.">
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
                data-tooltip="Must be a positive integer value. It should be greater than Lower count + 1, if filled in.">
                <input class="input is-danger trigger-details--alert-definition--rule--parameter
                trigger-details--alert-definition--elastic-search-query-rule--higher-count-input"
                type="text" value="{{ ($alertTriggerRule ?? false) ? $alertTriggerRule->parameters['higherCount'] : '' }}" placeholder="Higher count">
                <span class="icon is-small is-left">
                    <i class="fas fa-less-than-equal"></i>
                </span>
            </p>
        </div>
    </div>
</div>