@if ($alertTrigger ?? false)
    @include('configuration.alert-trigger.steps.content.watched-process-information')
    @include('layouts.title', [
        'title' => $alertTrigger->title,
        'titleSize' => '3',
        'icon' => 'dragon',
        'color' => 'primary'
    ])

    @foreach ($alertTrigger->definitions as $definition)
        @if ($loop->iteration % 3 === 0)
            </div>
        @endif

        @if ($loop->first || $loop->iteration % 3 === 0)
            <div class="columns">
        @endif

        <div class="column">
            @include('configuration.alert-trigger.steps.content.summary.alert-definition.element')
        </div>

        @if ($loop->last)
            </div>
        @endif
    @endforeach

    <article class="message is-info">
        <div class="message-body">
            By clicking on <strong>Next <span class="icon"><i class="fas fa-arrow-circle-right"></i></span></strong>
            button you'll validate the creation of this <strong><span class="icon"><i class="fas fa-dragon"></i></span> Alert trigger</strong>.
        </div>
    </article>
@endif