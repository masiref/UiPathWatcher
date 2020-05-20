@php
    $alertTrigger = ($alertTrigger ?? null);
    $wap = $alertTrigger ? $alertTrigger->watchedAutomatedProcess : null;
    $client_ = $wap ? $wap->client : null;
@endphp

@include('layouts.title', [
    'title' => 'Watched process selection',
    'icon' => 'binoculars',
    'color' => 'info',
    'titleSize' => '4'
])

<div class="field is-horizontal">
    <div class="field-body">
        <div class="field">
            <div class="control has-icons-left">
                <div class="select is-fullwidth">
                    <select class="client" {!! $alertTrigger ? 'disabled' : '' !!}>
                        <option value="0">Select a client</option>
                        @foreach ($clients as $client)
                            <option value="{{ $client->id }}"
                                {!! $client_ && $client_->id === $client->id ? 'selected' : '' !!}>{{ $client }}</option>
                        @endforeach
                    </select>
                </div>
                <span class="icon is-small is-left">
                    <i class="fas fa-building"></i>
                </span>
            </div>
        </div>
    </div>
</div>
<div class="field is-horizontal">
    <div class="field-body">
        <div class="field">
            <div class="control has-icons-left">
                <div class="select is-fullwidth">
                    <select class="watched-automated-process" disabled>
                        @if ($alertTrigger)
                            <option value="{{ $wap->id }}">{{ $wap }}</option>
                        @else
                            <option value="0">Select a watched process</option>
                        @endif
                    </select>
                </div>
                <span class="icon is-small is-left">
                    <i class="fas fa-binoculars"></i>
                </span>
            </div>
        </div>
    </div>
</div>