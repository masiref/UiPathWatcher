<div class="field is-horizontal">
    <div class="field-body">
        <div class="field">
            <p class="control is-expanded has-icons-left has-tooltip-bottom"
                data-tooltip="Duration is in minutes. Must be a positive integer value.">
                <input class="input is-danger trigger-details--alert-definition--rule--parameter
                    trigger-details--alert-definition--jobs-duration-rule--maximal-duration-input"
                    type="text" value="{{ ($alertTriggerRule ?? false) ? $alertTriggerRule->parameters['maximalDuration'] : '' }}"
                    placeholder="Maximal duration (minutes)">
                <span class="icon is-small is-left">
                    <i class="fas fa-less-than-equal"></i>
                </span>
            </p>
        </div>
    </div>
</div>