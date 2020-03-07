@extends('layouts.app')

@section('assets')
@endsection

@section('content')
    <div class="configuration">
        <h1 class="title">Orchestrators configuration</h1>

        <table class="table is-fullwidth is-striped is-hoverable">
            <thead>
                <th>Name</th>
                <th>URL</th>
                <th>Tenant</th>
                <th>Username</th>
                <th>Kibana URL</th>
                <th>Kibana Index</th>
            </thead>
        </table>
        <hr>
        <h1 class="title">Add new orchestrator</h1>
        <form action="#">
            <div class="field is-horizontal">
                <div class="field-body">
                    <div class="field">
                        <p class="control is-expanded">
                            <input class="input" id="name" type="text" placeholder="Name">
                        </p>
                    </div>
                </div>
            </div>
            <div class="field is-horizontal">
                <div class="field-body">
                    <div class="field">
                        <p class="control is-expanded">
                            <input class="input" id="url" type="text" placeholder="URL">
                        </p>
                    </div>
                    <div class="field">
                        <p class="control is-expanded">
                            <input class="input" id="tenant" type="text" placeholder="Tenant">
                        </p>
                    </div>
                </div>
            </div>
            <div class="field is-horizontal">
                <div class="field-body">
                    <div class="field">
                        <p class="control is-expanded">
                            <input class="input" id="api_user_username" type="text" placeholder="Username">
                        </p>
                    </div>
                    <div class="field">
                        <p class="control is-expanded">
                            <input class="input" id="api_user_password" type="password" placeholder="Password">
                        </p>
                    </div>
                </div>
            </div>
            <div class="field is-horizontal">
                <div class="field-body">
                    <div class="field">
                        <p class="control is-expanded">
                            <input class="input" id="kibana_url" type="text" placeholder="Kibana URL">
                        </p>
                    </div>
                    <div class="field">
                        <p class="control is-expanded">
                            <input class="input" id="kibana_index" type="text" placeholder="Kibana Index">
                        </p>
                    </div>
                </div>
            </div>
            <div class="field is-horizontal">
                <div class="field-body">
                    <div class="field">
                        <div class="control">
                            <button class="button is-primary">Create</button>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
@endsection
