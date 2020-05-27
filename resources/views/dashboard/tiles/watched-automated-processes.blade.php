<div class="tile is-parent watched-automated-processes">
    <article class="tile is-child box notification is-blue">
        <p class="title">
            @if ($client ?? false)
                {{ $clientWatchedAutomatedProcessesCount }}
            @else
                {{ $watchedAutomatedProcessesCount }}
            @endif
        </p>
        <p class="subtitle">
            <span class="icon"><i class="fas fa-binoculars"></i></span>
            <span>Watched process{{ $watchedAutomatedProcessesCount > 1 ? 'es' : ''}}</span>
        </p>
    </article>
</div>