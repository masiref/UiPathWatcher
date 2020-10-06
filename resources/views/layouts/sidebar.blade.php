<aside class="menu sidebar">
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
            @if ($clients->count() > 0)
                <ul>
                    @foreach($clients as $client)
                        <li>
                            <a class="{{ $page === 'dashboard.client.index.' . $client->id ? 'is-active' : '' }}"
                                href="{{ route('dashboard.client', ['client' => $client->id ]) }}">
                                <span class="icon"><i class="fas fa-building"></i></span>
                                <span>
                                    {{ $client->name }}
                                    &nbsp;<span class="tag is-{{ $client->higherAlertLevel() }}">{{ $client->openedAlertsCount() }}</span>
                                </span>
                            </a>
                        </li>
                    @endforeach
                </ul>
            @endif
        </li>
        @if (auth()->user()->alerts->count() > 0)
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
        @endif
    </ul>

    <!-- Configuration menu -->
    <p class="menu-label">
        <span class="icon"><i class="fas fa-cogs"></i></span>
        <span>Configuration</span>
    </p>
    <ul class="menu-list">
        @level(3)
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
                        Customers
                        &nbsp;<span class="tag is-primary">{{ $clientsCount }}</span>
                    </span>
                </a>
            </li>
        @endlevel
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
                    &nbsp;<span class="tag is-primary">{{ $alertTriggersCount }}</span>
                </span>
            </a>
        </li>
    </ul>

    @role('admin')
        <!-- Users management menu -->
        <p class="menu-label">
            <span class="icon"><i class="fas fa-users-cog"></i></span>
            <span>Users management</span>
        </p>
        <ul class="menu-list">
            <li>
                <a href="{{ route('users') }}">
                    <span class="icon"><i class="fas fa-users"></i></span>
                    <span>Show all users</span>
                </a>
            </li>
            <li>
                <a href="{{ route('users.create') }}">
                    <span class="icon"><i class="fas fa-user-plus"></i></span>
                    <span>Add a new user</span>
                </a>
            </li>
        </ul>
    @endrole
</aside>