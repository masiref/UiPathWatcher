<div class="field is-horizontal">
    <div class="field-body">
        <div class="field">
            <p class="control is-expanded has-icons-left has-tooltip-bottom"
                data-tooltip="Duration is in minutes. Must be a positive integer value.">
                <input class="input is-success trigger-details--alert-definition--rule--parameter
                    trigger-details--alert-definition--jobs-duration-rule--minimal-duration-input"
                    type="text" value="{{ ($alertTriggerRule ?? false) ? $alertTriggerRule->parameters['minimalDuration'] : '0' }}"
                    placeholder="Minimal duration (minutes)">
                <span class="icon is-small is-left">
                    <i class="fas fa-greater-than-equal"></i>
                </span>
            </p>
        </div>
    </div>
</div>