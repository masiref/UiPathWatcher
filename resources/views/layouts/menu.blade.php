<nav class="navbar has-shadow is-fixed-top menu">
    <div class="container">
        <div class="navbar-brand">
            <a href="{{ url('/') }}" class="navbar-item">{{ config('app.name', 'Laravel') }}</a>

            <div class="navbar-burger burger" data-target="navMenu">
                <span></span>
                <span></span>
                <span></span>
            </div>
        </div>
        <div class="navbar-menu" id="navMenu">
            @if (!Auth::guest())
                <div class="navbar-start">
                    <div class="navbar-item has-dropdown is-hoverable">
                        <a class="navbar-link" href="#">
                            <span class="icon"><i class="fas fa-chart-bar"></i></span>
                            <span>&nbsp;Dashboard</span>
                        </a>
                        <div class="navbar-dropdown">
                            <a class="{{ $page === 'dashboard.index' ? 'is-active' : '' }} navbar-item" href="{{ route('dashboard') }}">
                                <span class="icon"><i class="fas fa-globe"></i></span>
                                <span>&nbsp;Global</span>
                            </a>
                            @if ($clients->count() > 0)
                                <hr class="navbar-divider">
                                @foreach($clients as $client)
                                    <a class="{{ $page === 'dashboard.client.index.' . $client->id ? 'is-active' : '' }} navbar-item" href="{{ route('dashboard.client', ['client' => $client->id ]) }}">
                                        <span class="icon"><i class="fas fa-building"></i></span>
                                        <span>
                                            {{ $client->name }}
                                            &nbsp;<span class="tag is-{{ $client->higherAlertLevel() }}">{{ $client->openedAlertsCount() }}</span>
                                        </span>
                                    </a>
                                @endforeach
                            @endif
                            @if (auth()->user()->alerts->count() > 0)
                                <hr class="navbar-divider">
                                <a class="navbar-item {{ $page === 'dashboard.user.index' ? 'is-active' : '' }}" href="{{ route('dashboard.user') }}">
                                    <span class="icon"><i class="fas fa-burn"></i></span>
                                    <span>
                                        &nbsp;My alerts
                                        &nbsp;<span class="tag is-{{ auth()->user()->higherAlertLevel() }}">{{ auth()->user()->alerts->count() }}</span>
                                    </span>
                                </a>
                            @endif
                        </div>
                    </div>
                    <div class="navbar-item has-dropdown is-hoverable">
                        <a class="navbar-link" href="#">
                            <span class="icon"><i class="fas fa-cogs"></i></span>
                            <span>&nbsp;Configuration</span>
                        </a>

                        <div class="navbar-dropdown">
                            <a class="navbar-item {{ $page === 'configuration.orchestrator.index' ? 'is-active' : '' }}"
                                href="{{ route('configuration.orchestrator') }}">
                                <span class="icon"><i class="fas fa-server"></i></span>
                                <span>
                                    &nbsp;Orchestrators
                                    &nbsp;<span class="tag is-primary">{{ $orchestratorsCount }}</span>
                                </span>
                            </a>
                            <a class="navbar-item {{ $page === 'configuration.client.index' ? 'is-active' : '' }}"
                                href="{{ route('configuration.client') }}">
                                <span class="icon"><i class="fas fa-building"></i></span>
                                <span>
                                    &nbsp;Clients
                                    &nbsp;<span class="tag is-primary">{{ $clientsCount }}</span>
                                </span>
                            </a>
                            <a class="navbar-item {{ $page === 'configuration.watched-automated-process.index' ? 'is-active' : '' }}"
                                href="{{ route('configuration.watched-automated-process') }}">
                                <span class="icon"><i class="fas fa-binoculars"></i></span>
                                <span>
                                    &nbsp;Watched processes
                                    &nbsp;<span class="tag is-primary">{{ $watchedAutomatedProcessesCount }}</span>
                                </span>
                            </a>
                            <a class="navbar-item {{ $page === 'configuration.alert-trigger.index' ? 'is-active' : '' }}"
                                href="{{ route('configuration.alert-trigger') }}">
                                <span class="icon"><i class="fas fa-dragon"></i></span>
                                <span>
                                    &nbsp;Alert triggers
                                    &nbsp;<span class="tag is-primary">{{ $alertTriggersCount }}</span>
                                </span>
                            </a>
                        </div>
                    </div>
                </div>
            @endif

            <div class="navbar-end">
                @if (Auth::guest())
                    <a class="navbar-item " href="{{ route('login') }}">
                        <span class="icon"><i class="fas fa-sign-out-alt"></i></span>
                        <span>Login</span>
                    </a>
                @else
                    <div class="navbar-item has-dropdown is-hoverable">
                        <a class="navbar-link" href="#">
                            <span class="icon"><i class="fas fa-user-circle"></i></span>
                            <span>&nbsp;{{ Auth::user()->name }}</span>
                        </a>

                        <div class="navbar-dropdown">
                            <a class="navbar-item" href="{{ route('logout') }}"
                                onclick="event.preventDefault();document.getElementById('logout-form').submit();">
                                <span class="icon"><i class="fas fa-sign-out-alt"></i></span>
                                <span>
                                    &nbsp;Logout
                                </span>
                            </a>

                            <form id="logout-form" action="{{ route('logout') }}" method="POST"
                                    style="display: none;">
                                {{ csrf_field() }}
                            </form>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</nav>