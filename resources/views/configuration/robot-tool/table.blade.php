<table class="table data selectable is-fullwidth is-striped is-hoverable robot-tools">
    <thead>
        <th>Label</th>
        <th>Process name</th>
    </thead>
    <tbody>
        @foreach($robotTools as $robotTool)
            <tr data-id="{{ $robotTool->id }}">
                <td>{{ $robotTool->label }}</td>
                <td>{{ $robotTool->process_name }}</td>
            </tr>
        @endforeach
    </tbody>
</table>