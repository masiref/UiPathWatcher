<div class="field is-horizontal">
    <div class="field-body">
        <div class="field">
            <div class="control">
                <div class="buttons is-right">
                    @if ($alertTrigger ?? false)
                        @if (!$alertTrigger->deleted)
                            <button class="button is-{{ $alertTrigger->active ? 'danger' : 'success' }} is-outlined {{ $alertTrigger->active ? 'disable' : 'activate' }}">
                                <span class="icon is-small">
                                    <i class="fas fa-toggle-{{ $alertTrigger->active ? 'off' : 'on' }}"></i>
                                </span>
                                <span>{{ $alertTrigger->active ? 'Disable' : 'Activate' }}</span>
                            </button>
                            @if ($alertTrigger->active)
                                <button class="button is-{{ !$alertTrigger->ignored ? 'danger' : 'success' }} is-outlined {{ !$alertTrigger->ignored ? 'ignore' : 'acknowledge' }}">
                                    <span class="icon is-small">
                                        <i class="fas fa-eye{{ !$alertTrigger->ignored ? '-slash' : '' }}"></i>
                                    </span>
                                    <span>{{ !$alertTrigger->ignored ? 'Ignore' : 'Acknowledge' }}</span>
                                </button>
                            @endif
                        @endif
                        <button class="button is-dark is-outlined cancel">
                            <span class="icon is-small">
                                <i class="fas fa-times-circle"></i>
                            </span>
                            <span>Close</span>
                        </button>
                        <button class="button is-{{ $alertTrigger->deleted ? 'success' : 'danger' }} {{ $alertTrigger->deleted ? 'restore' : 'remove' }}">
                            <span class="icon is-small">
                                <i class="fas fa-trash{{ $alertTrigger->deleted ? '-restore' : '' }}-alt"></i>
                            </span>
                            <span>{{ $alertTrigger->deleted ? 'Restore' : 'Remove' }}</span>
                        </button>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>