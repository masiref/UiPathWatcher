<form id="add-form" action="#" onsubmit="return false;">
    <div class="field is-horizontal">
        <div class="field-body">
            <div class="field">
                <div class="control has-icons-left">
                    <div class="select is-fullwidth">
                        <select id="client">
                            <option value="0">Select a client</option>
                            @foreach ($clients as $client)
                                <option value="{{ $client->id }}">{{ $client }}</option>
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
                    <input class="input" id="name" type="text" placeholder="Name">
                    <span class="icon is-small is-left">
                        <i class="fas fa-signature"></i>
                    </span>
                </p>
            </div>
            <div class="field">
                <p class="control is-expanded has-icons-left">
                    <input class="input" id="code" type="text" placeholder="Code">
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
                    <input class="input" id="operational_handbook_page_url" type="text" placeholder="Operational handbook page URL">
                    <span class="icon is-small is-left">
                        <i class="fas fa-book"></i>
                    </span>
                </p>
            </div>
            <div class="field">
                <p class="control is-expanded has-icons-left">
                    <input class="input" id="kibana_dashboard_url" type="text" placeholder="Kibana dashboard URL">
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
                    <textarea class="textarea" id="additional_information" placeholder="Additional information"></textarea>
                </p>
            </div>
        </div>
    </div>
    <hr>
    <div class="running-period-section p-b-md">
        <h1 class="subtitle">Running period</h1>
        <div class="field is-horizontal">
            <div class="field-body">
                <div class="field">
                    <div class="columns">
                        <div class="column">
                            <p class="control">
                                <label class="checkbox">
                                    <input type="checkbox" id="running_period_monday">
                                    Monday
                                </label>
                            </p>
                        </div>
                        <div class="column">
                            <p class="control">
                                <label class="checkbox">
                                    <input type="checkbox" id="running_period_tuesday">
                                    Tuesday
                                </label>
                            </p>
                        </div>
                        <div class="column">
                            <p class="control">
                                <label class="checkbox">
                                    <input type="checkbox" id="running_period_wednesday">
                                    Wednesday
                                </label>
                            </p>
                        </div>
                        <div class="column">
                            <p class="control">
                                <label class="checkbox">
                                    <input type="checkbox" id="running_period_thursday">
                                    Thursday
                                </label>
                            </p>
                        </div>
                        <div class="column">
                            <p class="control">
                                <label class="checkbox">
                                    <input type="checkbox" id="running_period_friday">
                                    Friday
                                </label>
                            </p>
                        </div>
                        <div class="column">
                            <p class="control">
                                <label class="checkbox">
                                    <input type="checkbox" id="running_period_saturday">
                                    Saturday
                                </label>
                            </p>
                        </div>
                        <div class="column">
                            <p class="control">
                                <label class="checkbox">
                                    <input type="checkbox" id="running_period_sunday">
                                    Sunday
                                </label>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="field is-horizontal">
        <div class="field-body">
            <div class="field">
                <div class="control">
                    <input type="date" class="datetime" id="running_period_times">
                </div>
            </div>
        </div>
    </div>
    <hr>
    <div class="involved-processes-section p-b-md">
        <h1 class="subtitle">Involved UiPath processes &nbsp;<span class="tag is-rounded">0</span></h1>
        <table class="is-fullwidth is-striped is-hoverable involved-processes-table"></table>
    </div>
    <div class="field is-horizontal">
        <div class="field-body">
            <div class="field">
                <div class="control">
                    <div class="buttons">
                        <button class="button is-primary create" disabled>
                            <span class="icon is-small">
                                <i class="fas fa-plus-circle"></i>
                            </span>
                            <span>Create</span>
                        </button>
                        <button class="button is-danger is-outlined reset">
                            <span class="icon is-small">
                                <i class="fas fa-undo"></i>
                            </span>
                            <span>Reset</span>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>