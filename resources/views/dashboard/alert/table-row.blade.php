<tr id="alert-row-{{ $alert->id }}">
    <td class="has-tooltip-right" data-order="{{ $alert->levelOrder() }}"
        data-tooltip="Latest heartbeat {{ $alert->latest_heartbeat_at ? $alert->latestHeartbeatAtDiffForHumans() : $alert->createdAtDiffForHumans() }}">
        <span class="icon has-text-{{ $alert->definition->level }}">
            <i class="fas fa-burn"></i>
        </span>
        @if (!$alert->closed)
            <span class="icon heartbeat has-text-{{ $alert->alive ? 'success' : 'grey-light' }}">
                <i class="fas fa-{{ $alert->alive ? 'heartbeat' : 'heart-broken' }}"></i>
            </span>
        @endif
    </td>
    <td>#{{ str_pad($alert->id, 4, '0', STR_PAD_LEFT) }}</td>
    <td class="has-tooltip-right"
        data-tooltip="{{ $alert->client()->name }}">
        {{ $alert->client()->code }}
    </td>
    <td class="has-tooltip-right"
        data-tooltip="{{ $alert->watchedAutomatedProcess->name }}">
        {{ $alert->watchedAutomatedProcess->code }}
    </td>
    <td>{{ $alert->trigger->title }}</td>
    <td>{{ $alert->definition->description }}</td>
    @if ($options['closed'])
        <td>{{ $alert->categories->pluck('label')->join(', ') }}</td>
    @endif
    <td>
        @include('dashboard.alert.buttons', [
            'small' => true,
            'table' => true
        ])
    </td>
</tr>