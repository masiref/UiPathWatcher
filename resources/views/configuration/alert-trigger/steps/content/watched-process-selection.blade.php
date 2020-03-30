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
            <div class="control has-icons-left">
                <div class="select is-fullwidth">
                    <select id="watched-automated-process" disabled>
                        <option value="0">Select a watched process</option>
                    </select>
                </div>
                <span class="icon is-small is-left">
                    <i class="fas fa-binoculars"></i>
                </span>
            </div>
        </div>
    </div>
</div>