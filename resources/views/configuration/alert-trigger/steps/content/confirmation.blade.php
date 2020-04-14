@if ($alertTrigger ?? false)
    @include('configuration.alert-trigger.steps.content.watched-process-information')
    @include('layouts.title', [
        'title' => $alertTrigger->title,
        'titleSize' => '3',
        'icon' => 'dragon',
        'color' => 'primary'
    ])

    <div class="notification is-success has-text-centered">
        <p class="title is-5"><span class="icon is-large"><i class="fas fa-3x fa-check-circle"></i></span></p>
        <p class="title is-4">Alert trigger created successfully</p>
        <div class="is-divider"></div>
        <div class="buttons is-centered">
            <button class="button is-success is-large is-inverted is-outlined trigger-details--confirmation--activate-button"
                data-id="{{ $alertTrigger->id }}">
                <span class="icon"><i class="fas fa-toggle-on"></i></span>
                <span>Activate</span>
            </button>
        </div>
    </div>
@endif