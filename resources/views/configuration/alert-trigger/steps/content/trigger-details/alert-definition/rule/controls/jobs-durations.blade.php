<div class="field is-horizontal">
    <div class="field-body">
        <div class="field">
            <p class="control is-expanded has-icons-left has-tooltip-bottom"
                data-tooltip="Duration is in minutes. Must be a positive integer value.">
                <input class="input is-success trigger-details--alert-definition--rule--parameter
                    trigger-details--alert-definition--jobs-duration-rule--minimal-duration-input"
                    type="text" value="0" placeholder="Minimal duration (minutes)">
                <span class="icon is-small is-left">
                    <i class="fas fa-greater-than-equal"></i>
                </span>
            </p>
        </div>
        <!--
        <div class="field">
            <div class="control has-icons-left">
                <div class="select is-fullwidth is-success">
                    <select class="trigger-details--alert-definition--rule--parameter
                        trigger-details--alert-definition--jobs-duration-rule--minimal-duration-unit-select">
                        <option value="none">Select a unit</option>
                        <option value="second" selected>Seconds</option>
                        <option value="minute">Minutes</option>
                        <option value="hour">Hours</option>
                    </select>
                </div>
                <span class="icon is-small is-left">
                    <i class="fas fa-ruler"></i>
                </span>
            </div>
        </div>
        -->
        <div class="field">
            <p class="control is-expanded has-icons-left has-tooltip-bottom"
                data-tooltip="Duration is in minutes. Must be a positive integer value greater than Minimal duration.">
                <input class="input is-danger trigger-details--alert-definition--rule--parameter
                    trigger-details--alert-definition--jobs-duration-rule--maximal-duration-input"
                    type="text" placeholder="Maximal duration (minutes)">
                <span class="icon is-small is-left">
                    <i class="fas fa-less-than-equal"></i>
                </span>
            </p>
        </div>
        <!--
        <div class="field">
            <div class="control has-icons-left">
                <div class="select is-fullwidth is-danger">
                    <select class="trigger-details--alert-definition--rule--parameter
                        trigger-details--alert-definition--jobs-duration-rule--maximal-duration-unit-select">
                        <option value="none" selected>Select a unit</option>
                        <option value="second">Seconds</option>
                        <option value="minute">Minutes</option>
                        <option value="hour">Hours</option>
                    </select>
                </div>
                <span class="icon is-small is-left">
                    <i class="fas fa-ruler"></i>
                </span>
            </div>
        </div>
        -->
    </div>
</div>