@include('layouts.title', [
    'title' => 'Summary',
    'icon' => 'spell-check',
    'color' => 'info',
    'titleSize' => '4'
])

@if ($alertTrigger ?? false)
    @include('configuration.alert-trigger.steps.content.watched-process-information')
    <div>
        @include('layouts.title', [
            'title' => 'Details',
            'icon' => 'asterisk',
            'iconSize' => 'small',
            'color' => 'info',
            'titleSize' => '5'
        ])
        @include('layouts.title', [
            'title' => $alertTrigger->title,
            'titleSize' => '5',
            'icon' => 'dragon',
            'color' => 'primary',
            'underlined' => false
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
                button you'll validate the saving of this <strong><span class="icon"><i class="fas fa-dragon"></i></span> Alert trigger</strong>.
            </div>
        </article>
    </div>
@endif