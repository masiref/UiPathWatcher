<tr id="alert-row-{{ $alert->id }}">
    <td data-order="{{ $alert->levelOrder() }}">
        <span class="icon has-text-{{ $alert->definition->level }}">
            <i class="fas fa-burn"></i>
        </span>
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
    @if ($options['closed'])
        <td data-order="{{ $alert->closedAtTimestamp() }}">
            {{ $alert->closedAt() }}
        </td>
        <td>{{ $alert->false_positive ? 'Yes' : 'No' }}</td>
        <td>{{ $alert->ignored ? 'Yes' : 'No' }}</td>
    @endif
    <td>
        @include('dashboard.alert.buttons', [
            'small' => true,
            'table' => true
        ])
    </td>
</tr>