<table class="table data selectable is-fullwidth is-striped is-hoverable watched-automated-processes">
    <thead>
        <th>Client</th>
        <th>Name</th>
        <th>Code</th>
        <th>Processes</th>
        <th>Robots</th>
        <th>Queues</th>
        {{--<th>More</th>--}}
    </thead>
    <tbody>
        @foreach($watchedAutomatedProcesses as $wap)
            <tr data-id="{{ $wap->id }}">
                <td>{{ $wap->client }}</td>
                <td>{{ $wap->name }}</td>
                <td>{{ $wap->code }}</td>
                <td>
                    @if (count($wap->processes) > 0)
                        <ul>
                            @foreach ($wap->processes as $process)
                                <li>{{ $process }}</li>
                            @endforeach
                        </ul>
                    @endif
                </td>
                <td>
                    @if (count($wap->robots) > 0)
                        <ul>
                            @foreach ($wap->robots as $robot)
                                <li>{{ $robot }}</li>
                            @endforeach
                        </ul>
                    @endif
                </td>
                <td>
                    @if (count($wap->queues) > 0)
                        <ul>
                            @foreach ($wap->queues as $queue)
                                <li>{{ $queue }}</li>
                            @endforeach
                        </ul>
                    @endif
                </td>
                {{--<td>
                    <div class="buttons is-right">
                        <button class="button is-link has-tooltip-left is-small"
                            data-tooltip="{{ ($wap->additional_information ? $wap->additional_information . ' | ' : '') . $wap->runningPeriod() }}">
                            <span class="icon">
                                <i class="fas fa-info"></i>
                            </span>
                        </button>
                    </div>
                </td>--}}
            </tr>
        @endforeach
    </tbody>
</table>