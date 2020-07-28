<article class="message is-link">
    <div class="message-body has-text-grey-dark">
        <div class="level">
            <div class="level-left">
                <div class="level-item">
                    @include('layouts.title', [
                        'title' => $alertTrigger->watchedAutomatedProcess->client,
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
                        'title' => $alertTrigger->watchedAutomatedProcess . ' (' . $alertTrigger->watchedAutomatedProcess->code . ')' ,
                        'titleSize' => '5',
                        'icon' => 'binoculars',
                        'iconSize' => 'small',
                        'color' => 'link',
                        'underlined' => false
                    ])
                </div>
            </div>
        </div>

        @if ($alertTrigger->watchedAutomatedProcess->additional_information)
            @include('layouts.title', [
                'title' => $alertTrigger->watchedAutomatedProcess->additional_information,
                'titleSize' => '5',
                'icon' => 'info-circle',
                'iconSize' => 'small',
                'color' => 'info',
                'underlined' => false
            ])
        @endif

        @include('layouts.title', [
            'title' => $alertTrigger->watchedAutomatedProcess->runningPeriod(),
            'titleSize' => '5',
            'titleId' => 'process-running-period',
            'icon' => 'clock',
            'iconSize' => 'small',
            'color' => 'info',
            'underlined' => false
        ])

        @if (
            $alertTrigger->watchedAutomatedProcess->operational_handbook_page_url ||
            $alertTrigger->watchedAutomatedProcess->kibana_dashboard_url
        )
            <div class="level">
                <div class="level-left">
                    @if ($alertTrigger->watchedAutomatedProcess->operational_handbook_page_url)
                        @php
                            $operationalHandbookPageLink = "
                                <a href='{$alertTrigger->watchedAutomatedProcess->operational_handbook_page_url}' target='about:blank'>
                                    Operational handbook
                                </a>
                            ";
                        @endphp
                        <div class="level-item">
                            @include('layouts.title', [
                                'title' => $operationalHandbookPageLink,
                                'titleSize' => '5',
                                'icon' => 'book',
                                'iconSize' => 'small',
                                'color' => 'info',
                                'underlined' => false
                            ])
                        </div>
                    @endif
                    @if (
                        $alertTrigger->watchedAutomatedProcess->operational_handbook_page_url &&
                        $alertTrigger->watchedAutomatedProcess->kibana_dashboard_url
                    )
                        <div class="level-item">
                            <span class="icon has-text-link">
                                <i class="fas fa-dot-circle"></i>
                            </span>
                        </div>
                    @endif
                    @if ($alertTrigger->watchedAutomatedProcess->kibana_dashboard_url)
                        @php
                            $kibanaDashboardLink = "
                                <a href='{$alertTrigger->watchedAutomatedProcess->kibana_dashboard_url}' target='about:blank'>
                                    Kibana dashboard
                                </a>
                            ";
                        @endphp
                        <div class="level-item">
                            @include('layouts.title', [
                                'title' => $kibanaDashboardLink,
                                'titleSize' => '5',
                                'icon' => 'chart-bar',
                                'iconSize' => 'small',
                                'color' => 'info',
                                'underlined' => false
                            ])
                        </div>
                    @endif
                </div>
            </div>
        @endif
    </div>
</article>