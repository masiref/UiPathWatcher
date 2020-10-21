<table id="{{ $tableID ?? 'alerts-table' }}" class="table data unselectable is-fullwidth is-striped is-hoverable" {!! $options['closed'] ? 'data-order="[[ 1, &quot;desc&quot; ]]"' : '' !!}>
    <thead>
        <tr>
            <th>Level</th>
            <th>#ID</th>
            <th>Customer</th>
            <th><abbr title="Watched utomated process">Process</abbr></th>
            <th>Title</th>
            <th>Description</th>
            @if ($options['closed'])
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