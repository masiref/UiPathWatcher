<a class="panel-block">
    <span class="panel-icon">
        <i class="fas fa-sitemap" aria-hidden="true"></i>
    </span>
    Involving {{ $rule->processes->pluck('name', 'version')->map(function ($v, $k) { return $v . ' (v' . $k . ')'; })->join(', ', ' and ') }} UiPath Process{{ $rule->processes->count() > 1 ? 'es' : '' }}
</a>