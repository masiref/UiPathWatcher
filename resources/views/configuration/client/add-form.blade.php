<form id="add-form" action="#" onsubmit="return false;">
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
                <div class="control has-icons-left">
                    <div class="select is-fullwidth">
                        <select id="orchestrator">
                            <option value="0">Select an orchestrator</option>
                            @foreach ($orchestrators as $orchestrator)
                                <option value="{{ $orchestrator->id }}">{{ $orchestrator }}</option>
                            @endforeach
                        </select>
                    </div>
                    <span class="icon is-small is-left">
                        <i class="fas fa-server"></i>
                    </span>
                </div>
            </div>
        </div>
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