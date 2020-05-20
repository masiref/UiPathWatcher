<section class="section quick-board">
    <div class="columns is-multiline">
        @foreach ($client->watchedAutomatedProcesses()->get() as $watchedAutomatedProcess)
            <div class="column is-3 quick-board--item">
                <div class="notification box is-{{ $watchedAutomatedProcess->higherAlertLevel() }} quick-board--heading">
                    <p class="title is-3 has-text-centered">{{ $watchedAutomatedProcess->code }}</p>
                    <p class="subtitle has-text-centered">{{ $watchedAutomatedProcess->name }}</p>
                </div>
                <div class="notification is-{{ $watchedAutomatedProcess->higherAlertLevel() }} quick-board--details" style="display: none;">
                    <div class="level">
                        <div class="level-left">
                            <div class="level-item">
                                <span class="tag is-light is-{{ $watchedAutomatedProcess->openedAlerts()->count() === 0 ? 'success' : 'danger' }}">{{ $watchedAutomatedProcess->openedAlerts()->count() }}</span>
                            </div>
                            <div class="level-item">
                                Pending alert{{ $watchedAutomatedProcess->openedAlerts()->count() > 1 ? 's' : '' }}
                            </div>
                        </div>
                    </div>
                    <div class="level">
                        <div class="level-left">
                            <div class="level-item">
                                <span class="tag is-light is-{{ $watchedAutomatedProcess->onlineRobotsCount() === $watchedAutomatedProcess->robots->count() ? 'success' : 'danger' }}">
                                    {{ $watchedAutomatedProcess->onlineRobotsCount() }} / {{ $watchedAutomatedProcess->robots->count() }}
                                </span>
                            </div>
                            <div class="level-item">
                                Online robot{{ $watchedAutomatedProcess->onlineRobotsCount() > 1 ? 's' : '' }}
                            </div>
                        </div>
                    </div>
                    <div class="level">
                        <div class="level-left">
                            <div class="level-item">
                                <span class="tag is-light is-{{ $watchedAutomatedProcess->loggingRobotsCount() === $watchedAutomatedProcess->robots->count() ? 'success' : 'danger' }}">
                                    {{ $watchedAutomatedProcess->loggingRobotsCount() }} / {{ $watchedAutomatedProcess->robots->count() }}
                                </span>
                            </div>
                            <div class="level-item">
                                Logging robot{{ $watchedAutomatedProcess->loggingRobotsCount() > 1 ? 's' : '' }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</section>