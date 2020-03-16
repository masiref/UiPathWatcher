<table class="table is-fullwidth is-striped is-hoverable orchestrators">
    <thead>
        <th>Name</th>
        <th>URL</th>
        <th>Tenant</th>
        <th>Username</th>
        <th>Kibana URL</th>
        <th>Kibana Index</th>
    </thead>
    <tbody>
        @foreach($orchestrators as $orchestrator)
            <tr>
                <td>{{ $orchestrator->name }}</td>
                <td>{{ $orchestrator->url }}</td>
                <td>{{ $orchestrator->tenant }}</td>
                <td>{{ $orchestrator->api_user_username }}</td>
                <td>{{ $orchestrator->kibana_url }}</td>
                <td>{{ $orchestrator->kibana_index }}</td>
            </tr>
        @endforeach
    </tbody>
</table>