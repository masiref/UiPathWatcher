@if ($alert->under_revision)
    <article class="message is-info is-small">
        <div class="message-body">
            Under revision {{ $alert->reviewer->id === Auth::user()->id ? '' : 'by ' . $alert->reviewer->name }}
            <small class="has-tooltip-right" data-tooltip="{{ $alert->revisionStartedAt() }}">
                ({{ $alert->revisionStartedAtDiffForHumans() }})
            </small>
        </div>
    </article>
@endif