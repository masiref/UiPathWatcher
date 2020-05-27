<div class="tile is-parent alerts-not-closed">
    <article class="tile is-child box">
        <p class="title">{{ $openedAlertsCount }}</p>
        <p class="subtitle">
            <span class="icon"><i class="fas fa-fire"></i></span>
            <span>Pending alert{{ $openedAlertsCount > 1 ? 's' : ''}}</span>
        </p>
    </article>
</div>