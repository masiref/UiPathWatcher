<div class="buttons has-addons is-right">
    <!--<button
        class="button is-link has-tooltip-bottom comment-btn"
        data-tooltip="Comment"
        data-id="{{ $alert->id }}">
        <span class="icon is-large">
            <i class="fas fa-comment"></i>
        </span>
        <span>3</span>
    </button>-->
    <button
        class="button is-link has-tooltip-bottom timeline-btn {{ $small ?? '' ? 'is-small' : '' }}"
        data-tooltip="Timeline"
        data-id="{{ $alert->id }}">
        <span class="icon is-large">
            <i class="fas fa-stream"></i>
        </span>
    </button>
    @if (!$alert->closed)
        @if ($alert->under_revision)
            @if ($alert->reviewer->id === Auth::user()->id)
                <button
                    class="button is-success has-tooltip-bottom close-btn {{ $small ?? '' ? 'is-small' : '' }}"
                    data-tooltip="Close"
                    data-id="{{ $alert->id }}">
                    <span class="icon is-large">
                        <i class="fas fa-fire-extinguisher"></i>
                    </span>
                </button>
                <button class="button is-light has-tooltip-bottom ignore-btn {{ $small ?? '' ? 'is-small' : '' }}"
                    data-tooltip="Ignore trigger"
                    data-id="{{ $alert->id }}">
                    <span class="icon is-large">
                        <i class="fas fa-eye-slash"></i>
                    </span>
                </button>
                <button class="button is-{{ $alert->definition->level }} has-tooltip-bottom cancel-btn {{ $small ?? '' ? 'is-small' : '' }}"
                    data-tooltip="Cancel review"
                    data-id="{{ $alert->id }}">
                    <span class="icon is-large">
                        <i class="fas fa-times"></i>
                    </span>
                </button>
            @elseif ($table ?? false)
                <button class="button is-link has-tooltip-bottom info-btn {{ $small ?? '' ? 'is-small' : '' }}"
                    data-tooltip="Under revision by {{ $alert->reviewer->name }} ({{ $alert->revisionStartedAtDiffForHumans() }})"
                    data-id="{{ $alert->id }}">
                    <span class="icon is-large">
                        <i class="fas fa-info"></i>
                    </span>
                </button>
            @endif
        @else
            <button class="button is-{{ $alert->definition->level }} has-tooltip-bottom revision-btn {{ $small ?? '' ? 'is-small' : '' }}"
                data-tooltip="Review"
                data-id="{{ $alert->id }}">
                <span class="icon is-large">
                    <i class="fas fa-glasses"></i>
                </span>
            </button>
        @endif
    @endif
</div>