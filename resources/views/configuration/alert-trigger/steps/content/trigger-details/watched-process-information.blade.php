<article class="message is-link">
    <div class="message-body has-text-grey-dark">
        <div class="level">
            <div class="level-left">
                <div class="level-item">
                    @include('layouts.title', [
                        'title' => $alertTrigger->watchedAutomatedProcess->client,
                        'titleSize' => '4',
                        'icon' => 'building',
                        'color' => 'link'
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
                        'titleSize' => '4',
                        'icon' => 'binoculars',
                        'color' => 'link'
                    ])
                </div>
            </div>
        </div>

        @if ($alertTrigger->watchedAutomatedProcess->additional_information)
            @include('layouts.title', [
                'title' => $alertTrigger->watchedAutomatedProcess->additional_information,
                'titleSize' => '6',
                'icon' => 'info-circle',
                'iconSize' => 'small',
                'color' => 'info'
            ])
        @endif

        @include('layouts.title', [
            'title' => $alertTrigger->watchedAutomatedProcess->runningPeriod(),
            'titleSize' => '6',
            'titleId' => 'process-running-period',
            'icon' => 'play',
            'iconSize' => 'small',
            'color' => 'info'
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
                                'titleSize' => '6',
                                'icon' => 'book',
                                'iconSize' => 'small',
                                'color' => 'info'
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
                                'titleSize' => '6',
                                'icon' => 'chart-bar',
                                'iconSize' => 'small',
                                'color' => 'info'
                            ])
                        </div>
                    @endif
                </div>
            </div>
        @endif
    </div>
</article>