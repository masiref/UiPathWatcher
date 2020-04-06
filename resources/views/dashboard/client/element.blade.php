<div class="card events-card client-box" id="client-{{ $client->id }}" data-id="{{ $client->id }}">
    <header class="card-header">
        <p class="card-header-title">
            <span class="icon"><i class="fas fa-building"></i></span>
            <span>
                &nbsp;{{ $client->name }}
                &nbsp;<span class="tag is-{{ $client->higherAlertLevel() }}">{{ $client->openedAlertsCount() }}</span>
            </span>
        </p>
    </header>
    <div class="card-content">
        @forelse ($client->watchedAutomatedProcesses()->get() as $watchedAutomatedProcess)
            @include('dashboard.watched-automated-process.element')
        @empty
            <article class="message is-info">
                <div class="message-body">
                    There is no <strong><span class="icon"><i class="fas fa-binoculars"></i></span> Watched automated process</strong>
                </div>
            </article>
        @endforelse
    </div>
</div>