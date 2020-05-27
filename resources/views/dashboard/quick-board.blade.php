<section class="section quick-board">
    <div class="columns is-multiline">
        @foreach ($clients as $client)
            <div class="column is-3 quick-board--item">
                <div class="notification box is-{{ $client->higherAlertLevel() }} quick-board--heading">
                    <p class="title is-3 has-text-centered">{{ $client->code }}</p>
                    <p class="subtitle has-text-centered">{{ $client->name }}</p>
                </div>
                <div class="notification is-{{ $client->higherAlertLevel() }} quick-board--details" style="display: none;">
                    <div class="level">
                        <div class="level-left">
                            <div class="level-item">
                                <span class="tag is-light is-{{ $client->openedAlertsCount() === 0 ? 'success' : 'danger' }}">{{ $client->openedAlertsCount() }}</span>
                            </div>
                            <div class="level-item">
                                Pending alert{{ $client->openedAlertsCount() > 1 ? 's' : '' }}
                            </div>
                        </div>
                    </div>
                    @if ($client->watchedAutomatedProcesses->count() > 0)
                        <div class="level">
                            <div class="level-left">
                                <div class="level-item">
                                    <span class="tag is-light is-{{ $client->onlineRobotsCount() === $client->robotsCount() ? 'success' : 'danger' }}">
                                        {{ $client->onlineRobotsCount() }} / {{ $client->robotsCount() }}
                                    </span>
                                </div>
                                <div class="level-item">
                                    Online robot{{ $client->onlineRobotsCount() > 1 ? 's' : '' }}
                                </div>
                            </div>
                        </div>
                        <div class="level">
                            <div class="level-left">
                                <div class="level-item">
                                    <span class="tag is-light is-{{ $client->loggingRobotsCount() === $client->robotsCount() ? 'success' : 'grey-light' }}">
                                        {{ $client->loggingRobotsCount() }} / {{ $client->robotsCount() }}
                                    </span>
                                </div>
                                <div class="level-item">
                                    Logging robot{{ $client->loggingRobotsCount() > 1 ? 's' : '' }}
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        @endforeach
    </div>
</section>