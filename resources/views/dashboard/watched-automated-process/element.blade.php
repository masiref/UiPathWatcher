<article
    id="watched-automated-process-{{ $watchedAutomatedProcess->id }}"
    class="media watched-automated-process-box {{ $autonomous ?? false ? 'has-background-white' : '' }}"
    data-id="{{ $watchedAutomatedProcess->id }}">
    <div class="media-content">
        <div class="content">
            <p>
                <span class="icon is-medium has-text-{{ $watchedAutomatedProcess->higherAlertLevel() }}">
                    <i class="fas fa-binoculars"></i>
                </span>
                <strong>{{ $watchedAutomatedProcess->code }} | {{ $watchedAutomatedProcess->name }}</strong>
            </p>

            <div class="is-divider" data-content="General information"></div>
            @if ($watchedAutomatedProcess->additional_information)
                @include('layouts.title', [
                    'title' => $watchedAutomatedProcess->additional_information,
                    'titleSize' => '6',
                    'icon' => 'info-circle',
                    'iconSize' => 'small',
                    'color' => 'info',
                    'underlined' => false
                ])
            @endif
            @include('layouts.title', [
                'title' => $watchedAutomatedProcess->runningPeriod(),
                'titleSize' => '6',
                'icon' => 'clock',
                'iconSize' => 'small',
                'color' => 'info',
                'underlined' => false
            ])
            @if ($watchedAutomatedProcess->operational_handbook_page_url)
                @include('layouts.title', [
                    'title' => '<a href="' . $watchedAutomatedProcess->operational_handbook_page_url . '" target="about:blank">Operational handbook</a>',
                    'titleSize' => '6',
                    'icon' => 'book',
                    'iconSize' => 'small',
                    'color' => 'info',
                    'underlined' => false
                ])
            @endif
            @if ($watchedAutomatedProcess->kibana_dashboard_url)
                @include('layouts.title', [
                    'title' => '<a href="' . $watchedAutomatedProcess->kibana_dashboard_url . '" target="about:blank">Kibana dashboard</a>',
                    'titleSize' => '6',
                    'icon' => 'chart-bar',
                    'iconSize' => 'small',
                    'color' => 'info',
                    'underlined' => false
                ])
            @endif
            
            @if ($watchedAutomatedProcess->robots->count() > 0)
                <div class="is-divider" data-content="Robots"></div>
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
                            <p class="title has-tooltip-bottom has-text-{{ $robot->level() === 'warning' ? 'grey-light' : $robot->level() }}"
                                data-tooltip="{{ (strlen($robot) > 8 ? $robot . ' - ' : '') }}{{ $robot->username }} {{ $robot->description ? '(' . $robot->description . ')' : '' }}">
                                <span class="icon is-small">
                                    <i class="fab fa-android"></i>
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
        
        <div class="is-divider" data-content="Alerts"></div>
        @forelse($watchedAutomatedProcess->openedAlerts() as $alert)
            @include('dashboard.alert.element')
        @empty
            <article class="message is-success">
                <div class="message-body">
                    <span class="icon"><i class="fas fa-thumbs-up"></i></span> Everything is under control
                </div>
            </article>
        @endforelse
    </div>
</article>
