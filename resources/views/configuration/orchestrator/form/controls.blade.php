<div class="field is-horizontal">
    <div class="field-body">
        <div class="field">
            <p class="control is-expanded has-icons-left">
                <input class="input name" type="text" placeholder="Name"
                    value="{{ ($orchestrator ?? false) ? $orchestrator->name : '' }}">
                <span class="icon is-small is-left">
                    <i class="fas fa-signature"></i>
                </span>
            </p>
        </div>
        <div class="field">
            <p class="control is-expanded has-icons-left">
                <input class="input code" type="text" placeholder="Code"
                    value="{{ ($orchestrator ?? false) ? $orchestrator->code : '' }}">
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
                <input class="input url" type="text" placeholder="URL"
                    value="{{ ($orchestrator ?? false) ? $orchestrator->url : '' }}">
                <span class="icon is-small is-left">
                    <i class="fas fa-link"></i>
                </span>
            </p>
        </div>
        <div class="field">
            <p class="control is-expanded has-icons-left">
                <input class="input tenant" type="text" placeholder="Tenant"
                    value="{{ ($orchestrator ?? false) ? $orchestrator->tenant : '' }}">
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
                <input class="input api-user-username" type="text" placeholder="Username"
                    value="{{ ($orchestrator ?? false) ? $orchestrator->api_user_username : '' }}">
                <span class="icon is-small is-left">
                    <i class="fas fa-user-ninja"></i>
                </span>
            </p>
        </div>
        <div class="field">
            <p class="control is-expanded has-icons-left">
                <input class="input api-user-password" type="password" placeholder="Password"
                    value="{{ ($orchestrator ?? false) ? $orchestrator->api_user_password : '' }}">
                <span class="icon is-small is-left">
                    <i class="fas fa-key"></i>
                </span>
            </p>
        </div>
    </div>
</div>