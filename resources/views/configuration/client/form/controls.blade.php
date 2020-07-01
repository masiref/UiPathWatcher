<div class="field is-horizontal">
    <div class="field-body">
        <div class="field">
            <div class="control has-icons-left">
                <div class="select is-fullwidth">
                    <select class="orchestrator">
                        <option value="0">Select an orchestrator</option>
                        @foreach ($orchestrators as $orchestrator)
                            <option value="{{ $orchestrator->id }}"
                                {!! ($client ?? false) && $client->orchestrator->id === $orchestrator->id ? 'selected' : '' !!}>
                                {{ $orchestrator }}
                            </option>
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
            <p class="control is-expanded has-icons-left">
                <input class="input name" type="text" placeholder="Name" value="{{ ($client ?? false) ? $client->name : '' }}">
                <span class="icon is-small is-left">
                    <i class="fas fa-signature"></i>
                </span>
            </p>
        </div>
        <div class="field">
            <p class="control is-expanded has-icons-left">
                <input class="input code" type="text" placeholder="Code" value="{{ ($client ?? false) ? $client->code : '' }}">
                <span class="icon is-small is-left">
                    <i class="fas fa-barcode"></i>
                </span>
            </p>
        </div>
    </div>
</div>
<div class="is-divider" data-content="UiPath Orchestrator configuration"></div>
<div class="field is-horizontal">
    <div class="field-body">
        <div class="field">
            <p class="control is-expanded has-icons-left">
                <input class="input orchestrator-tenant" type="text" placeholder="Tenant"
                    value="{{ ($client ?? false) ? $client->ui_path_orchestrator_tenant : '' }}">
                <span class="icon is-small is-left">
                    <i class="fas fa-box"></i>
                </span>
            </p>
        </div>
    </div>
</div>
<div class="field is-horizontal">
    <div class="field-body">
        <div class="field">
            <p class="control is-expanded has-icons-left">
                <input class="input orchestrator-api-user-username" type="text" placeholder="Username"
                    value="{{ ($client ?? false) ? $client->ui_path_orchestrator_api_user_username : '' }}">
                <span class="icon is-small is-left">
                    <i class="fas fa-user-ninja"></i>
                </span>
            </p>
        </div>
        <div class="field">
            <p class="control is-expanded has-icons-left">
                <input class="input orchestrator-api-user-password" type="password" placeholder="Password"
                    value="{{ ($client ?? false) ? $client->ui_path_orchestrator_api_user_password : '' }}">
                <span class="icon is-small is-left">
                    <i class="fas fa-key"></i>
                </span>
            </p>
        </div>
    </div>
</div>
<div class="is-divider" data-content="ElasticSearch configuration"></div>
<div class="field is-horizontal">
    <div class="field-body">
        <div class="field">
            <p class="control is-expanded has-icons-left">
                <input class="input elastic-search-url" type="text" placeholder="ElasticSearch URL"
                    value="{{ ($client ?? false) ? $client->elastic_search_url : '' }}">
                <span class="icon is-small is-left">
                    <i class="fas fa-chart-bar"></i>
                </span>
            </p>
        </div>
        <div class="field">
            <p class="control is-expanded has-icons-left">
                <input class="input elastic-search-index" type="text" placeholder="ElasticSearch Index"
                    value="{{ ($client ?? false) ? $client->elastic_search_index : '' }}">
                <span class="icon is-small is-left">
                    <i class="fas fa-bookmark"></i>
                </span>
            </p>
        </div>
    </div>
</div>
<div class="field is-horizontal">
    <div class="field-body">
        <div class="field">
            <p class="control is-expanded has-icons-left">
                <input class="input elastic-search-api-user-username" type="text" placeholder="Username"
                    value="{{ ($client ?? false) ? $client->elastic_search_api_user_username : '' }}">
                <span class="icon is-small is-left">
                    <i class="fas fa-user-ninja"></i>
                </span>
            </p>
        </div>
        <div class="field">
            <p class="control is-expanded has-icons-left">
                <input class="input elastic-search-api-user-password" type="password" placeholder="Password"
                    value="{{ ($client ?? false) ? $client->elastic_search_api_user_password : '' }}">
                <span class="icon is-small is-left">
                    <i class="fas fa-key"></i>
                </span>
            </p>
        </div>
    </div>
</div>