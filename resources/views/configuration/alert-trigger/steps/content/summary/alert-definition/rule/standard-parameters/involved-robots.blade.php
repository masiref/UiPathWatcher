<a class="panel-block">
    <span class="panel-icon">
        <i class="fas fa-robot" aria-hidden="true"></i>
    </span>
    Involving {{ $rule->robots->pluck('name')->join(', ', ' and ') }} UiPath Robot{{ $rule->robots->count() > 1 ? 's' : '' }}
</a>