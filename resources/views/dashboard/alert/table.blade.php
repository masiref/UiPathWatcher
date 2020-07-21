<table id="{{ $tableID ?? 'alerts-table' }}" class="table data unselectable is-fullwidth is-striped is-hoverable">
    <thead>
        <tr>
            <th>Level</th>
            <th>#</th>
            <th>Customer</th>
            <th><abbr title="Automated process">A. Pr.</abbr></th>
            <th>Title</th>
            @if ($options['closed'])
                <th>Closed at</th>
                <th>False positive?</th>
                <th>Ignored?</th>
                <th>Categories</th>
            @endif
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        @foreach($alerts as $alert)
            @include('dashboard.alert.table-row')
        @endforeach
    </tbody>
</table>