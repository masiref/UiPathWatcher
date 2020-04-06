<article
    id="watched-automated-process-{{ $watchedAutomatedProcess->id }}"
    class="media watched-automated-process-box {{ $autonomous ?? '' ? 'has-background-white p-md' : '' }}"
    data-id="{{ $watchedAutomatedProcess->id }}">
    <div class="media-content">
        <div class="content">
            <p>
                <span class="icon is-medium has-text-{{ $watchedAutomatedProcess->higherAlertLevel() }}">
                    <i class="fas fa-binoculars"></i>
                </span>
                <strong>{{ $watchedAutomatedProcess->name }}</strong>
            </p>
            @if ($watchedAutomatedProcess->additional_information
                || $watchedAutomatedProcess->operational_handbook_page_url
                || $watchedAutomatedProcess->kibana_dashboard_url)
                <article class="message is-primary is-small">
                    <div class="message-body">
                        @if ($watchedAutomatedProcess->additional_information)
                            {{ $watchedAutomatedProcess->additional_information }}
                        @endif
                        @include('dashboard.watched-automated-process.buttons')
                    </div>
                </article>
            @endif
            @if ($watchedAutomatedProcess->robots->count() > 0)
                @foreach($watchedAutomatedProcess->robots as $robot)
                    @if ($loop->iteration % 6 === 0)
                            </div>
                        </div>
                    @endif
                    @if ($loop->iteration % 6 === 0 || $loop->first)
                        <div class="level">
                            <div class="level-left">
                    @endif
                    <div class="level-item has-text-centered p-sm">
                        <div>
                            <p class="heading has-text-weight-bold">{{ strlen($robot) > 8 ? substr($robot, 0, 5) . '...' : $robot }}</p>
                            <p class="title has-tooltip-bottom has-text-{{ $robot->level() }}"
                                data-tooltip="{{ (strlen($robot) > 8 ? $robot . ' - ' : '') }}{{ $robot->username }} {{ $robot->description ? '(' . $robot->description . ')' : '' }}">
                                <span class="icon is-small">
                                    <i class="fas fa-robot"></i>
                                </span>
                            </p>
                        </div>
                    </div>
                    @if ($loop->last)
                            </div>
                        </div>
                    @endif
                @endforeach
            @endif
        </div>
        @forelse($watchedAutomatedProcess->openedAlerts() as $alert)
            @include('dashboard.alert.element')
        @empty
            <article class="message is-success">
                <div class="message-body">
                    There is no <strong><span class="icon"><i class="fas fa-burn"></i></span> Alert</strong>
                </div>
            </article>
        @endforelse
    </div>
</article>
