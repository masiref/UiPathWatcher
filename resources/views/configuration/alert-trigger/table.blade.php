<table class="table data selectable is-fullwidth is-striped is-hoverable alert-triggers">
    <thead>
        <th>#</th>
        <th>Customer</th>
        <th>Process</th>
        <th>Title</th>
        <th>Is active?</th>
        <th>Is ignored?</th>
        {{--<th>More</th>--}}
    </thead>
    <tbody>
        @foreach($alertTriggers as $alertTrigger)
            <tr data-id="{{ $alertTrigger->id }}">
                <td>#{{ str_pad($alertTrigger->id, 4, '0', STR_PAD_LEFT) }}</td>
                <td>{{ $alertTrigger->watchedAutomatedProcess->client }}</td>
                <td>{{ $alertTrigger->watchedAutomatedProcess }}</td>
                <td>{{ $alertTrigger->title }}</td>
                <td>{{ $alertTrigger->active ? 'Yes' : 'No' }}</td>
                <td>{{ $alertTrigger->ignored ? 'Yes' : 'No' }}</td>
                {{--<td></td>--}}
            </tr>
        @endforeach
    </tbody>
</table>