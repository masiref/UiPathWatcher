<div class="tile is-parent client">
    <article class="tile is-child box notification is-{{ $client->higherAlertLevel() }}">
        <p class="title">{{ $client->code }}</p>
        <p class="subtitle">
            <span class="icon"><i class="fas fa-building"></i></span>
            <span>{{ $client->name }}</span>
        </p>
    </article>
</div>