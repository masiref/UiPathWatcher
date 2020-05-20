<div class="field is-horizontal">
    <div class="field-body">
        <div class="field">
            <div class="control has-icons-left">
                <div class="select is-fullwidth">
                    <select class="client"
                        {!! ($watchedAutomatedProcess ?? false) ? 'disabled' : '' !!}>
                        <option value="0">Select a client</option>
                        @foreach ($clients as $client)
                            <option value="{{ $client->id }}"
                                {!! ($watchedAutomatedProcess ?? false) && $watchedAutomatedProcess->client->id === $client->id ? 'selected' : '' !!}>
                                {{ $client }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <span class="icon is-small is-left">
                    <i class="fas fa-building"></i>
                </span>
            </div>
        </div>
    </div>
</div>
<div class="field is-horizontal">
    <div class="field-body">
        <div class="field">
            <p class="control is-expanded has-icons-left">
                <input class="input name" type="text" placeholder="Name" value="{{ ($watchedAutomatedProcess ?? false) ? $watchedAutomatedProcess->name : '' }}">
                <span class="icon is-small is-left">
                    <i class="fas fa-signature"></i>
                </span>
            </p>
        </div>
        <div class="field">
            <p class="control is-expanded has-icons-left">
                <input class="input code" type="text" placeholder="Code" value="{{ ($watchedAutomatedProcess ?? false) ? $watchedAutomatedProcess->code : '' }}">
                <span class="icon is-small is-left">
                    <i class="fas fa-barcode"></i>
                </span>
            </p>
        </div>
    </div>
</div>
<div class="field is-horizontal">
    <div class="field-body">
        <div class="field">
            <p class="control is-expanded has-icons-left">
                <input class="input operational_handbook_page_url" type="text"
                    placeholder="Operational handbook page URL"
                    value="{{ ($watchedAutomatedProcess ?? false) ? $watchedAutomatedProcess->operational_handbook_page_url : '' }}">
                <span class="icon is-small is-left">
                    <i class="fas fa-book"></i>
                </span>
            </p>
        </div>
        <div class="field">
            <p class="control is-expanded has-icons-left">
                <input class="input kibana_dashboard_url" type="text"
                    placeholder="Kibana dashboard URL"
                    value="{{ ($watchedAutomatedProcess ?? false) ? $watchedAutomatedProcess->kibana_dashboard_url : '' }}">
                <span class="icon is-small is-left">
                    <i class="fas fa-chart-bar"></i>
                </span>
            </p>
        </div>
    </div>
</div>
<div class="field is-horizontal">
    <div class="field-body">
        <div class="field">
            <p class="control">
                <textarea class="textarea additional_information"
                    placeholder="Additional information"
                    value="{{ ($watchedAutomatedProcess ?? false) ? $watchedAutomatedProcess->additional_information : '' }}"></textarea>
            </p>
        </div>
    </div>
</div>
    
<div class="is-divider"></div>
<div class="running-period-section p-b-md">
    @include('layouts.subtitle', [
        'title' => 'Execution time slot',
        'icon' => 'clock'
    ])
    @php
        $uniqid = uniqid();
    @endphp
    <div class="field is-horizontal">
        <div class="field-body">
            <div class="field">
                <div class="columns">
                    <div class="column">
                        <p class="control">
                            <input type="checkbox" id="running_period_monday_{{ $uniqid }}" class="running_period_monday switch is-small is-rounded"
                                {!! ($watchedAutomatedProcess ?? false) && $watchedAutomatedProcess->running_period_monday ? 'checked="checked"' : '' !!}>
                            <label for="running_period_monday_{{ $uniqid }}" class="checkbox">Monday</label>
                        </p>
                    </div>
                    <div class="column">
                        <p class="control">
                            <input type="checkbox" id="running_period_tuesday_{{ $uniqid }}" class="running_period_tuesday switch is-small is-rounded"
                                {!! ($watchedAutomatedProcess ?? false) && $watchedAutomatedProcess->running_period_tuesday ? 'checked="checked"' : '' !!}>
                            <label for="running_period_tuesday_{{ $uniqid }}" class="checkbox">Tuesday</label>
                        </p>
                    </div>
                    <div class="column">
                        <p class="control">
                            <input type="checkbox" id="running_period_wednesday_{{ $uniqid }}" class="running_period_wednesday switch is-small is-rounded"
                                {!! ($watchedAutomatedProcess ?? false) && $watchedAutomatedProcess->running_period_wednesday ? 'checked="checked"' : '' !!}>
                            <label for="running_period_wednesday_{{ $uniqid }}" class="checkbox">Wednesday</label>
                        </p>
                    </div>
                    <div class="column">
                        <p class="control">
                            <input type="checkbox" id="running_period_thursday_{{ $uniqid }}" class="running_period_thursday switch is-small is-rounded"
                                {!! ($watchedAutomatedProcess ?? false) && $watchedAutomatedProcess->running_period_thursday ? 'checked="checked"' : '' !!}>
                            <label for="running_period_thursday_{{ $uniqid }}" class="checkbox">Thursday</label>
                        </p>
                    </div>
                    <div class="column">
                        <p class="control">
                            <input type="checkbox" id="running_period_friday_{{ $uniqid }}" class="running_period_friday switch is-small is-rounded"
                                {!! ($watchedAutomatedProcess ?? false) && $watchedAutomatedProcess->running_period_friday ? 'checked="checked"' : '' !!}>
                            <label for="running_period_friday_{{ $uniqid }}" class="checkbox">Friday</label>
                        </p>
                    </div>
                    <div class="column">
                        <p class="control">
                            <input type="checkbox" id="running_period_saturday_{{ $uniqid }}" class="running_period_saturday switch is-small is-rounded"
                                {!! ($watchedAutomatedProcess ?? false) && $watchedAutomatedProcess->running_period_saturday ? 'checked="checked"' : '' !!}>
                            <label for="running_period_saturday_{{ $uniqid }}" class="checkbox">Saturday</label>
                        </p>
                    </div>
                    <div class="column">
                        <p class="control">
                            <input type="checkbox" id="running_period_sunday_{{ $uniqid }}" class="running_period_sunday switch is-small is-rounded"
                                {!! ($watchedAutomatedProcess ?? false) && $watchedAutomatedProcess->running_period_sunday ? 'checked="checked"' : '' !!}>
                            <label for="running_period_sunday_{{ $uniqid }}" class="checkbox">Sunday</label>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="field is-horizontal">
        <div class="field-body">
            <div class="field">
                <div class="control">
                    <input type="date" class="datetime running_period_times"
                        data-start-time="{{ ($watchedAutomatedProcess ?? false) ? substr($watchedAutomatedProcess->running_period_time_from, 0, 5) : '' }}"
                        data-end-time="{{ ($watchedAutomatedProcess ?? false) ? substr($watchedAutomatedProcess->running_period_time_until, 0, 5) : '' }}">
                </div>
            </div>
        </div>
    </div>
</div>

<div class="is-divider"></div>
<div class="involved-processes-section p-b-md">
    @include('layouts.subtitle', [
        'title' => 'Involved UiPath processes &nbsp;<span class="tag is-rounded">0</span>',
        'icon' => 'sitemap'
    ])
    <table class="is-fullwidth is-striped is-hoverable involved-processes-table"
        {!! ($watchedAutomatedProcess ?? false) ? 'data-selected="' . $watchedAutomatedProcess->processes->pluck('external_id') . '"' : '' !!}>
    </table>
</div>

<div class="is-divider"></div>
<div class="involved-robots-section p-b-md">
    @include('layouts.subtitle', [
        'title' => 'Involved UiPath robots &nbsp;<span class="tag is-rounded">0</span>',
        'icon' => 'android',
        'iconType' => 'fab'
    ])
    <table class="is-fullwidth is-striped is-hoverable involved-robots-table"
        {!! ($watchedAutomatedProcess ?? false) ? 'data-selected="' . $watchedAutomatedProcess->robots->pluck('external_id') . '"' : '' !!}>
    </table>
</div>

<div class="is-divider"></div>
<div class="involved-queues-section p-b-md">
    @include('layouts.subtitle', [
        'title' => 'Involved UiPath queues &nbsp;<span class="tag is-rounded">0</span>',
        'icon' => 'layer-group'
    ])
    <table class="is-fullwidth is-striped is-hoverable involved-queues-table"
        {!! ($watchedAutomatedProcess ?? false) ? 'data-selected="' . $watchedAutomatedProcess->queues->pluck('external_id') . '"' : '' !!}>
    </table>
</div>