<table class="table data selectable is-fullwidth is-striped is-hoverable orchestrators">
    <thead>
        <th>Name</th>
        <th>Code</th>
        <th>URL</th>
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
            </tr>
        @endforeach
    </tbody>
</table>