<table id="{{ $tableID ?? 'alerts-table' }}" class="table is-fullwidth is-striped is-hoverable">
    <thead>
        <tr>
            <th>Level</th>
            <th>ID</th>
            <th>Client</th>
            <th><abbr title="Automated process">A. Pr.</abbr></th>
            <th>Title</th>
            @if ($options['closed'])
                <th>Closed at</th>
                <th>False positive?</th>
                <th>Ignored?</th>
            @endif
            @if (!$options['closed'])
                <th>Actions</th>
            @endif
        </tr>
    </thead>
    <tbody>
        @foreach($alerts as $alert)
            @include('dashboard.alert.table-row')
        @endforeach
    </tbody>
</table>