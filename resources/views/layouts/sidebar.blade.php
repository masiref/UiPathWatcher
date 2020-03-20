<aside class="menu is-hidden-mobile sidebar">
    <!-- Dashboard menu -->
    <p class="menu-label">
        <span class="icon"><i class="fas fa-chart-bar"></i></span>
        <span>Dashboard</span>
    </p>
    <ul class="menu-list">
        <li>
            <a class="{{ $page === 'dashboard.index' ? 'is-active' : '' }}" href="{{ route('dashboard') }}">
                <span class="icon"><i class="fas fa-globe"></i></span>
                <span>Global</span>
            </a>
            <ul>
                @foreach($clients as $client)
                    <li>
                        <a class="{{ $page === 'dashboard.client.index.' . $client->id ? 'is-active' : '' }}"
                            href="{{ route('dashboard.client', ['client' => $client->id ]) }}">
                            <span class="icon"><i class="fas fa-building"></i></span>
                            <span>
                                {{ $client->code }} ({{ $client->name }})
                                &nbsp;<span class="tag is-{{ $client->higherAlertLevel() }}">{{ $client->openedAlertsCount() }}</span>
                            </span>
                        </a>
                    </li>
                @endforeach
            </ul>
        </li>
        <li>
            <a class="{{ $page === 'dashboard.user.index' ? 'is-active' : '' }}" href="{{ route('dashboard.user') }}">
                <span class="icon"><i class="fas fa-burn"></i></span>
                <span>
                    My alerts
                    &nbsp;
                    <span class="tag is-{{ auth()->user()->higherAlertLevel() }}">
                        {{ auth()->user()->openedAlerts()->count() }}
                    </span>
                </span>
            </a>
        </li>
    </ul>

    <!-- Configuration menu -->
    <p class="menu-label">
        <span class="icon"><i class="fas fa-cogs"></i></span>
        <span>Configuration</span>
    </p>
    <ul class="menu-list">
        <li>
            <a class="{{ $page === 'configuration.orchestrator.index' ? 'is-active' : '' }}"
                href="{{ route('configuration.orchestrator') }}">
                <span class="icon"><i class="fas fa-server"></i></span>
                <span>
                    Orchestrators
                    &nbsp;<span class="tag is-primary">{{ $orchestratorsCount }}</span>
                </span>
            </a>
        </li>
        <li>
            <a class="{{ $page === 'configuration.client.index' ? 'is-active' : '' }}"
                href="{{ route('configuration.client') }}">
                <span class="icon"><i class="fas fa-building"></i></span>
                <span>
                    Clients
                    &nbsp;<span class="tag is-primary">{{ $clientsCount }}</span>
                </span>
            </a>
        </li>
        <li>
            <a class="{{ $page === 'configuration.watched-automated-process.index' ? 'is-active' : '' }}"
                href="{{ route('configuration.watched-automated-process') }}">
                <span class="icon"><i class="fas fa-binoculars"></i></span>
                <span>
                    Watched processes
                    &nbsp;<span class="tag is-primary">{{ $watchedAutomatedProcessesCount }}</span>
                </span>
            </a>
        </li>
        <li>
            <a class="{{ $page === 'configuration.alert-trigger.index' ? 'is-active' : '' }}"
                href="{{ route('configuration.alert-trigger') }}">
                <span class="icon"><i class="fas fa-dragon"></i></span>
                <span>
                    Alert triggers
                    &nbsp;<span class="tag is-primary">99</span>
                </span>
            </a>
        </li>
    </ul>
</aside>