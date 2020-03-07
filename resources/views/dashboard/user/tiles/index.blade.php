<section class="info-tiles">
    <div class="tile is-ancestor has-text-centered">
        @include('dashboard.user.tiles.alerts-not-closed')
        @include('dashboard.user.tiles.alerts-under-revision')
        @include('dashboard.user.tiles.alerts-closed')
    </div>
</section>