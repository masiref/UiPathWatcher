<table class="table data is-fullwidth is-striped is-hoverable orchestrators">
    <thead>
        <th>Name</th>
        <th>Code</th>
        <th>URL</th>
        <th>Tenant</th>
        <th>Username</th>
        <th>ElasticSearch URL</th>
        <th>ElasticSearch Index</th>
    </thead>
    <tbody>
        @foreach($orchestrators as $orchestrator)
            <tr data-id="{{ $orchestrator->id }}">
                <td>{{ $orchestrator->name }}</td>
                <td>{{ $orchestrator->code }}</td>
                <td>
                    <a href="{{ $orchestrator->url }}" target="about:blank">
                        {{ $orchestrator->url }}
                    </a>
                </td>
                <td>{{ $orchestrator->tenant }}</td>
                <td>{{ $orchestrator->api_user_username }}</td>
                <td>
                    <a href="{{ $orchestrator->elastic_search_url }}" target="about:blank">
                        {{ $orchestrator->elastic_search_url }}
                    </a>
                </td>
                <td>{{ $orchestrator->elastic_search_index }}</td>
            </tr>
        @endforeach
    </tbody>
</table>