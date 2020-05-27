<div class="tile is-parent alerts-closed">
    <article class="tile is-child box notification is-grey">
        <p class="title">{{ $closedAlertsCount }}</p>
        <p class="subtitle">
            <span class="icon"><i class="fas fa-dumpster-fire"></i></span>
            <span>Closed alert{{ $closedAlertsCount > 1 ? 's' : ''}}</span>
        </p>
    </article>
</div>