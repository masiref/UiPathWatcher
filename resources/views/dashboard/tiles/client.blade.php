<div class="tile is-parent client">
    <article class="tile is-child box notification is-{{ $client->higherAlertLevel() }}">
        <p class="title">{{ $client->code }}</p>
        <p class="subtitle">{{ $client->name }}</p>
    </article>
</div>