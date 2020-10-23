<p class="alert-box__general-info">
    <span class="icon has-text-{{ $alert->definition->level }}">
        <i class="fas fa-burn"></i>
    </span>
    <span class="has-tooltip-right" data-tooltip="Latest heartbeat {{ $alert->latest_heartbeat_at ? $alert->latestHeartbeatAtDiffForHumans() : $alert->createdAtDiffForHumans() }}">
        <span class="icon {{ $alert->alive ? 'has-text-success' : 'has-text-grey-light' }}">
            <i class="fas fa-{{ $alert->alive ? 'heartbeat : 'heart-broken' }}"></i>
        </span>
    </span>
    <strong>#{{ str_pad($alert->id, 4, '0', STR_PAD_LEFT) }}</strong> | {{ $alert->trigger->title }}
    <small class="has-tooltip-right" data-tooltip="{{ $alert->createdAt() }}">
        (Raised {{ $alert->createdAtDiffForHumans() }})
    </small>
</p>