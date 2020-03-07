<section class="info-tiles">
    <div class="tile is-ancestor has-text-centered">
        @if ($client ?? false)
            @include('dashboard.tiles.client')
        @else
            @include('dashboard.tiles.clients')
        @endif
        @include('dashboard.tiles.watched-automated-processes')
        @include('dashboard.tiles.robots')
    </div>
</section>
<section class="info-tiles">
    <div class="tile is-ancestor has-text-centered">
        @include('dashboard.tiles.alerts-not-closed')
        @include('dashboard.tiles.alerts-under-revision')
        @include('dashboard.tiles.alerts-closed')
    </div>
</section>