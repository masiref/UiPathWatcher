<div class="tile is-parent watched-automated-processes">
    <article class="tile is-child box">
        <p class="title">
            @if ($client ?? false)
                {{ $clientWatchedAutomatedProcessesCount }}
            @else
                {{ $watchedAutomatedProcessesCount }}
            @endif
        </p>
        <p class="subtitle">Watched process{{ $watchedAutomatedProcessesCount > 1 ? 'es' : ''}}</p>
    </article>
</div>