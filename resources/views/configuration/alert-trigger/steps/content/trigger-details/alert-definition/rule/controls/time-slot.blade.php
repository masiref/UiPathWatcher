<!-- time controls -->
<div class="columns" style="margin-bottom: 0;">
    <div class="column">
        <!-- time slot -->
        <label class="label">Time slot</label>
        <div class="field-body">
            <div class="field">
                <div class="control is-expanded has-tooltip-bottom"
                    data-tooltip="Must be defined between {{ substr($watchedAutomatedProcess->running_period_time_from, 0, 5) }} and {{ substr($watchedAutomatedProcess->running_period_time_until, 0, 5) }}">
                    <input type="date" class="datetime trigger-details--alert-definition--rule--parameter
                        trigger-details--alert-definition--rule--time-slot-input"
                        data-start-time="{{ substr($watchedAutomatedProcess->running_period_time_from, 0, 5) }}"
                        data-end-time="{{ substr($watchedAutomatedProcess->running_period_time_until, 0, 5) }}">
                </div>
            </div>
        </div>
    </div>
    @if ($withRelative ?? true)
        <div class="column">
            <!-- relative time slot -->
            <label class="label">Relative time slot (optional)</label>
            <div class="field-body">
                <div class="field">
                    <p class="control is-expanded has-icons-left has-tooltip-bottom"
                        data-tooltip="Duration is in minutes. Must be a positive integer value.">
                        <input class="input is-danger trigger-details--alert-definition--rule--parameter
                            trigger-details--alert-definition--rule--relative-time-slot-input"
                            type="text" placeholder="Relative time slot (minutes)">
                        <span class="icon is-small is-left">
                            <i class="fas fa-history"></i>
                        </span>
                    </p>
                </div>
                <!--
                <div class="field">
                    <div class="control has-icons-left">
                        <div class="select is-fullwidth is-danger">
                            <select class="trigger-details--alert-definition--rule--parameter
                                trigger-details--alert-definition--rule--relative-time-slot-unit-select">
                                <option value="0" selected>Select a unit</option>
                                <option value="1">Seconds</option>
                                <option value="2">Minutes</option>
                                <option value="3">Hours</option>
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
    @endif
</div>