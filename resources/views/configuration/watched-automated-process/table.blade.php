<table class="table is-fullwidth is-striped is-hoverable watched-automated-processes">
    <thead>
        <th>Client</th>
        <th>Name</th>
        <th>Code</th>
        <th>Processes</th>
        {{--<th>Operational handbook</th>
        <th>Kibana dashboard</th>--}}
        <th>More</th>
    </thead>
    <tbody>
        @foreach($watchedAutomatedProcesses as $wap)
            <tr>
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
                {{--<td>
                    <a href="{{ $wap->operational_handbook_page_url }}" target="about:blank">
                        {{ $wap->operational_handbook_page_url }}
                    </a>
                </td>
                <td>
                    <a href="{{ $wap->kibana_dashboard_url }}" target="about:blank">
                        {{ $wap->kibana_dashboard_url }}
                    </a>
                </td>--}}
                <td>
                    <div class="buttons is-right">
                        <button class="button is-link has-tooltip-left is-small"
                            data-tooltip="{{ $wap->additional_information ? $wap->additional_information . ' | ' : '' }}Running from {{ $wap->running_period_time_from }} until {{ $wap->running_period_time_until }} on {{ $wap->runningDays() }}">
                            <span class="icon">
                                <i class="fas fa-info"></i>
                            </span>
                        </button>
                    </div>
                </td>
            </tr>
        @endforeach
    </tbody>
</table>