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
        </div>
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
        @foreach($watchedAutomatedProcess->openedAlerts() as $alert)
            @include('dashboard.alert.element')
        @endforeach
    </div>
</article>
