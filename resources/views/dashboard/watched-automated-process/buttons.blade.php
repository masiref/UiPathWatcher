<div class="buttons has-addons is-right">
    @if ($watchedAutomatedProcess->operational_handbook_page_url)
        <a href="{{ $watchedAutomatedProcess->operational_handbook_page_url }}"
            class="button is-link is-outlined has-tooltip-bottom"
            data-tooltip="Operational Handbook"
            target="about:blank">
            <span class="icon is-small">
                <i class="fas fa-book"></i>
            </span>
        </a>
    @endif
    @if ($watchedAutomatedProcess->kibana_dashboard_url)
        <a href="{{ $watchedAutomatedProcess->kibana_dashboard_url }}"
            class="button is-link is-outlined has-tooltip-bottom"
            data-tooltip="Kibana Dashboard"
            target="about:blank">
            <span class="icon is-small">
                <i class="fas fa-chart-bar"></i>
            </span>
        </a>
    @endif
</div>