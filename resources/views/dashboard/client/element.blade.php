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
        @foreach ($client->watchedAutomatedProcesses()->get() as $watchedAutomatedProcess)
            @include('dashboard.watched-automated-process.element')
        @endforeach
    </div>
</div>