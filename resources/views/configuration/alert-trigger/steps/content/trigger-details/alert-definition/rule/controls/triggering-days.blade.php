@php
    $uniqid = uniqid();
@endphp

<div class="triggering-days-section">
    @include('layouts.title', [
        'title' => 'Triggering days',
        'icon' => 'calendar-day',
        'color' => $level,
        'titleSize' => '5'
    ])
    <div class="field is-horizontal">
        <div class="field-body">
            <div class="field">
                <div class="columns">
                    <div class="column">
                        <p class="control">
                            <input type="checkbox" id="{{ $uniqid }}-triggering_day_monday"
                                class="switch is-small is-rounded trigger-details--alert-definition--rule--parameter
                                    trigger-details--alert-definition--rule--triggering-day-monday"
                                {!! !$watchedAutomatedProcess->running_period_monday ? 'disabled' : $alertTriggerRule->is_triggered_on_monday ? 'checked="checked"' : '' !!}>
                            <label for="{{ $uniqid }}-triggering_day_monday" class="checkbox">Monday</label>
                        </p>
                    </div>
                    <div class="column">
                        <p class="control">
                            <input type="checkbox" id="{{ $uniqid }}-triggering_day_tuesday"
                                class="switch is-small is-rounded trigger-details--alert-definition--rule--parameter
                                    trigger-details--alert-definition--rule--triggering-day-tuesday"
                                {!! !$watchedAutomatedProcess->running_period_tuesday ? 'disabled' : $alertTriggerRule->is_triggered_on_tuesday ? 'checked="checked"' : '' !!}>
                            <label for="{{ $uniqid }}-triggering_day_tuesday" class="checkbox">Tuesday</label>
                        </p>
                    </div>
                    <div class="column">
                        <p class="control">
                            <input type="checkbox" id="{{ $uniqid }}-triggering_day_wednesday"
                                class="switch is-small is-rounded trigger-details--alert-definition--rule--parameter
                                    trigger-details--alert-definition--rule--triggering-day-wednesday"
                                {!! !$watchedAutomatedProcess->running_period_wednesday ? 'disabled' : $alertTriggerRule->is_triggered_on_wednesday ? 'checked="checked"' : '' !!}>
                            <label for="{{ $uniqid }}-triggering_day_wednesday" class="checkbox">Wednesday</label>
                        </p>
                    </div>
                    <div class="column">
                        <p class="control">
                            <input type="checkbox" id="{{ $uniqid }}-triggering_day_thursday"
                                class="switch is-small is-rounded trigger-details--alert-definition--rule--parameter
                                    trigger-details--alert-definition--rule--triggering-day-thursday"
                                {!! !$watchedAutomatedProcess->running_period_thursday ? 'disabled' : $alertTriggerRule->is_triggered_on_thursday ? 'checked="checked"' : '' !!}>
                            <label for="{{ $uniqid }}-triggering_day_thursday" class="checkbox">Thursday</label>
                        </p>
                    </div>
                    <div class="column">
                        <p class="control">
                            <input type="checkbox" id="{{ $uniqid }}-triggering_day_friday"
                                class="switch is-small is-rounded trigger-details--alert-definition--rule--parameter
                                    trigger-details--alert-definition--rule--triggering-day-friday"
                                {!! !$watchedAutomatedProcess->running_period_friday ? 'disabled' : $alertTriggerRule->is_triggered_on_friday ? 'checked="checked"' : '' !!}>
                            <label for="{{ $uniqid }}-triggering_day_friday" class="checkbox">Friday</label>
                        </p>
                    </div>
                    <div class="column">
                        <p class="control">
                            <input type="checkbox" id="{{ $uniqid }}-triggering_day_saturday"
                                class="switch is-small is-rounded trigger-details--alert-definition--rule--parameter
                                    trigger-details--alert-definition--rule--triggering-day-saturday"
                                {!! !$watchedAutomatedProcess->running_period_saturday ? 'disabled' : $alertTriggerRule->is_triggered_on_saturday ? 'checked="checked"' : '' !!}>
                            <label for="{{ $uniqid }}-triggering_day_saturday" class="checkbox">Saturday</label>
                        </p>
                    </div>
                    <div class="column">
                        <p class="control">
                            <input type="checkbox" id="{{ $uniqid }}-triggering_day_sunday"
                                class="switch is-small is-rounded trigger-details--alert-definition--rule--parameter
                                    trigger-details--alert-definition--rule--triggering-day-sunday"
                                {!! !$watchedAutomatedProcess->running_period_sunday ? 'disabled' : $alertTriggerRule->is_triggered_on_sunday ? 'checked="checked"' : '' !!}>
                            <label for="{{ $uniqid }}-triggering_day_sunday" class="checkbox">Sunday</label>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>