<div class="field is-horizontal">
    <div class="field-body">
        <div class="field">
            <p class="control is-expanded has-icons-left has-icons-right has-tooltip-bottom"
                data-tooltip="Must be a positive integer value.">
                <input class="input is-danger trigger-details--alert-definition--rule--parameter
                    trigger-details--alert-definition--failed-queue-items-percentage-rule--maximal-percentage-input"
                    type="text" value="{{ ($alertTriggerRule ?? false) ? $alertTriggerRule->parameters['maximalPercentage'] : '' }}"
                    placeholder="Maximal failed queue items percentage">
                <span class="icon is-small is-left">
                    <i class="fas fa-less-than-equal"></i>
                </span>
                <span class="icon is-small is-right">
                    <i class="fas fa-percentage"></i>
                </span>
            </p>
        </div>
    </div>
</div>