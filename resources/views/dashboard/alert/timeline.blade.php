@php
    $level = $alert->closed ? 'success' : $alert->definition->level;
@endphp

<div id="alert-timeline-modal-{{ $alert->id }}" class="modal">
    <div class="modal-background"></div>
    <div class="modal-card modal-content">
        <header class="modal-card-head has-background-{{ $level }}">
            <p class="modal-card-title has-text-light">
                <span class="icon"><i class="fas fa-stream"></i></span>
                #{{ str_pad($alert->id, 4, '0', STR_PAD_LEFT) }} | {{ $alert->trigger->title }}
                timeline
            </p>
            <button class="delete" aria-label="close"></button>
        </header>
        <section class="modal-card-body">
            <div class="level">
                <div class="level-left">
                    <div class="level-item">
                        @include('layouts.title', [
                            'title' => $alert->watchedAutomatedProcess->client,
                            'titleSize' => '5',
                            'icon' => 'building',
                            'iconSize' => 'small',
                            'color' => 'link',
                            'underlined' => false
                        ])
                    </div>
                    <div class="level-item">
                        <span class="icon has-text-link">
                            <i class="fas fa-chevron-circle-right"></i>
                        </span>
                    </div>
                    <div class="level-item">
                        @include('layouts.title', [
                            'title' => $alert->watchedAutomatedProcess . ' (' . $alert->watchedAutomatedProcess->code . ')' ,
                            'titleSize' => '5',
                            'icon' => 'binoculars',
                            'iconSize' => 'small',
                            'color' => 'link',
                            'underlined' => false
                        ])
                    </div>
                </div>
            </div>
            <div class="level">
                <div class="level-left">
                    <div class="level-item">
                        @include('layouts.title', [
                            'title' => $alert->createdAtDiffForHumans(),
                            'titleSize' => '5',
                            'icon' => 'clock',
                            'iconSize' => 'small',
                            'color' => 'link',
                            'underlined' => false
                        ])
                    </div>
                    @if ($alert->reviewer)
                        <div class="level-item">
                            <span class="icon has-text-link">
                                <i class="fas fa-chevron-circle-right"></i>
                            </span>
                        </div>
                        <div class="level-item">
                            @include('layouts.title', [
                                'title' => ($alert->closed ? 'Reviewed' : 'Under revision') . ' by ' . ($alert->reviewer->id === Auth::user()->id ? 'you' : $alert->reviewer->name),
                                'titleSize' => '5',
                                'icon' => 'user',
                                'iconSize' => 'small',
                                'color' => $level,
                                'underlined' => false
                            ])
                        </div>
                    @endif
                    <div class="level-item">
                        <span class="icon has-text-link">
                            <i class="fas fa-chevron-circle-right"></i>
                        </span>
                    </div>
                    <div class="level-item">
                        @include('layouts.title', [
                            'title' => 'Latest heartbeat ' . $alert->latest_heartbeat_at ? $alert->latestHeartbeatAtDiffForHumans() : $alert->createdAtDiffForHumans(),
                            'titleSize' => '5',
                            'icon' =>  $alert->alive ? 'heartbeat' : 'heart-broken',
                            'iconSize' => 'small',
                            'color' => $alert->alive ? 'success' : 'grey-light',
                            'underlined' => false
                        ])
                    </div>
                </div>
            </div>
            <div class="timeline is-centered">
                <header class="timeline-header">
                    <span class="tag is-medium is-{{ $level }}">
                        <span class="icon"><i class="fas fa-flag-checkered"></i></span> 
                        <span>Now</span>
                    </span>
                </header>
                <div class="timeline-item is-{{ $level }}">
                    <div class="timeline-marker is-icon is-{{ $level }}">
                        <i class="fa fa-burn"></i>
                    </div>
                    <div class="timeline-content">
                        <p class="heading">{{ $alert->createdAtDayDateTime() }}</p>
                        <p class="p-b-sm has-text-{{ $alert->definition->level }}">
                            <span class="icon"><i class="fas fa-dragon"></i></span>
                            Definition #<span class="has-text-weight-semibold">{{ str_pad($alert->definition->id, 4, '0', STR_PAD_LEFT) }}</span>
                            {{ $alert->definition->description ? ' / ' . $alert->definition->description : '' }}
                        </p>
                        <table class="table is-bordered is-striped is-hoverable is-fullwidth">
                            <thead>
                                <th>Date</th>
                                <th>Event</th>
                            </thead>
                            <tbody>
                                @if ($alert->closed)
                                    <tr class="is-selected">
                                        @php
                                            $date = $alert->closedAt();
                                        @endphp
                                        <td>{{ $date }}</td>
                                        <td>Alert closed: {{ $alert->closing_description }}</td>
                                    </tr>
                                @endif
                                @php
                                    $messages = $alert->messages;
                                @endphp
                                @if ($messages)
                                    @foreach (array_slice($messages, 0, 10) as $event)
                                        <tr>
                                            <td>{{ is_array($event) ? $event[0] : '' }}</td>
                                            <td>{{ is_array($event) ? $event[1] : $event }}</td>
                                        </tr>
                                    @endforeach
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>
                @foreach ($ancestors as $ancestor)
                    <div class="timeline-item is-{{ $ancestor->definition->level }}">
                        <div class="timeline-marker is-icon is-{{ $ancestor->definition->level }}">
                            <i class="fa fa-burn"></i>
                        </div>
                        <div class="timeline-content">
                            <p class="heading">{{ $ancestor->createdAtDayDateTime() }}</p>
                            <p class="p-b-sm has-text-{{ $ancestor->definition->level }}">
                                <span class="icon"><i class="fas fa-dragon"></i></span>
                                Definition #<span class="has-text-weight-semibold">{{ str_pad($ancestor->definition->id, 4, '0', STR_PAD_LEFT) }}</span>
                            </p>
                            <table class="table is-bordered is-striped is-hoverable is-fullwidth">
                                <thead>
                                    <th>Date</th>
                                    <th>Event</th>
                                </thead>
                                <tbody>
                                    @php
                                        $messages = $ancestor->messages;
                                    @endphp
                                    @if ($messages)
                                        @foreach (array_slice($messages, 0, 10) as $event)
                                            <tr>
                                                <td>{{ is_array($event) ? $event[0] : '' }}</td>
                                                <td>{{ is_array($event) ? $event[1] : $event }}</td>
                                            </tr>
                                        @endforeach
                                    @endif
                                </tbody>
                            </table>
                        </div>
                    </div>
                @endforeach
                <div class="timeline-header">
                    <span class="tag is-medium is-primary">
                        <span class="icon"><i class="fas fa-dot-circle"></i></span> 
                        <span>Start</span>
                    </span>
                </div>
            </div>
        </section>
        <footer class="modal-card-foot has-background-{{ $level }}">
            <div class="field is-grouped has-addons">
                <div class="control">
                    <button class="button is-dark is-outlined is-inverted cancel">
                        <span class="icon is-small">
                            <i class="fas fa-times"></i>
                        </span>
                        <span>Close</span>
                    </button>
                </div>
            </div>
        </footer>
    </div>
</div>
