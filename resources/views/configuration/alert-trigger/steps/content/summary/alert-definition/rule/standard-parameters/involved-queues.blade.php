<a class="panel-block">
    <span class="panel-icon">
        <i class="fas fa-layer-group" aria-hidden="true"></i>
    </span>
    Involving {{ $rule->queues->pluck('name')->join(', ', ' and ') }} UiPath Queue{{ $rule->queues->count() > 1 ? 's' : '' }}
</a>