<table class="table is-fullwidth is-striped is-hoverable clients">
    <thead>
        <th>Name</th>
        <th>Code</th>
        <th>Orchestrator URL</th>
        <th>Orchestrator Tenant</th>
    </thead>
    <tbody>
        @foreach($clients as $client)
            <tr data-id="{{ $client->id }}">
                <td>{{ $client->name }}</td>
                <td>{{ $client->code }}</td>
                <td>
                    <a href="{{ $client->orchestrator->url }}" target="about:blank">
                        {{ $client->orchestrator->url }}
                    </a>
                </td>
                <td>{{ $client->orchestrator->tenant }}</td>
            </tr>
        @endforeach
    </tbody>
</table>