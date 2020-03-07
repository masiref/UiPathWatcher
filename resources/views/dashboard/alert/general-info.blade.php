<p class="alert-box__general-info">
    <span class="icon has-text-{{ $alert->level }}">
        <i class="fas fa-burn"></i>
    </span>
    <strong>#{{ str_pad($alert->id, 4, '0', STR_PAD_LEFT) }}</strong> | {{ $alert->label }}
    <small class="has-tooltip-right" data-tooltip="{{ $alert->createdAt() }}">
        (Raised {{ $alert->createdAtDiffForHumans() }})
    </small>
</p>