@include('layouts.title', [
    'title' => 'Confirmation',
    'icon' => 'clipboard-check',
    'color' => 'success',
    'titleSize' => '5'
])
@if ($alertTrigger ?? false)
    @include('configuration.alert-trigger.steps.content.watched-process-information')
    <div class="notification is-success has-text-centered">
        <p class="title is-5 m-t-md"><span class="icon is-large"><i class="fas fa-3x fa-dragon"></i></span></p>
        <p class="title is-4">
            {{ $alertTrigger->title }} successfully saved!
        </p>
        <div class="is-divider"></div>
        <div class="buttons is-centered">
            <button class="button is-success is-large is-inverted is-outlined trigger-details--confirmation--activate-button"
                data-id="{{ $alertTrigger->id }}">
                <span class="icon"><i class="fas fa-toggle-on"></i></span>
                <span>Activate</span>
            </button>
            <button class="button is-dark is-large is-inverted is-outlined trigger-details--confirmation--close-button"
                data-id="{{ $alertTrigger->id }}">
                <span class="icon"><i class="fas fa-times"></i></span>
                <span>Close</span>
            </button>
        </div>
    </div>
@endif