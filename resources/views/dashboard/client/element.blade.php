<div class="card events-card client-box" id="client-{{ $client->id }}" data-id="{{ $client->id }}">
    <header class="card-header">
        <p class="card-header-title">
            <span class="icon"><i class="fas fa-building"></i></span>
            <span>
                &nbsp;{{ $client->name }}
                &nbsp;<span class="tag is-{{ $client->higherAlertLevel() }}">{{ $client->openedAlertsCount() }}</span>
            </span>
        </p>
		<a href="#collapsible-client-{{ $client->id }}" data-action="collapse" class="card-header-icon is-hidden-fullscreen" aria-label="more options">
			<span class="icon">
				<i class="fas fa-angle-down" aria-hidden="true"></i>
			</span>
		</a>
    </header>
    <div id="collapsible-client-{{ $client->id }}" class="is-collapsible {{ ($collapsed ?? true) ? '' : 'active' }}">
        <div class="card-content">
            <article class="panel is-info">
                <a href="{{ $client->orchestrator->url }}" target="about:blank" class="panel-block is-active">
                    <span class="panel-icon">
                        <i class="fas fa-server" aria-hidden="true"></i>
                    </span>
                    UiPath Orchestrator tenant is &nbsp;<strong>{{ $client->ui_path_orchestrator_tenant }}</strong>
                </a>
                <a href="{{ $client->elastic_search_url }}" target="about:blank" class="panel-block is-active">
                    <span class="panel-icon">
                        <i class="fas fa-chart-bar" aria-hidden="true"></i>
                    </span>
                    ElasticSearch index is &nbsp;<strong>{{ $client->elastic_search_index }}</strong>
                </a>
            </article>
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
</div>