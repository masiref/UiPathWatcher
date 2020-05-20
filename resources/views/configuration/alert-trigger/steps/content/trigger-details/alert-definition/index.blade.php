@php
    $count = 0;
    if ($alertTrigger ?? false) {
        $count = $alertTrigger->definitions->where('deleted', false)->count();
    }
    $title = 'Alert definitions &nbsp;<span class="tag is-rounded is-primary">' . $count .'</span>';
@endphp

<div class="alert-definitions-section p-b-md">
    @include('layouts.title', [
        'title' => $title,
        'titleId' => 'alert-definitions-section-title',
        'titleSize' => '4',
        'icon' => 'burn',
        'color' => 'primary'
    ])

    <div class="alert-definitions-list">
        @if ($alertTrigger ?? false)
            @foreach ($alertTrigger->definitions->where('deleted', false) as $alertTriggerDefinition)
                @include('configuration.alert-trigger.steps.content.trigger-details.alert-definition.element')
            @endforeach
        @endif
    </div>

    <!-- add alert definition button -->
    <div class="field is-horizontal p-t-lg">
        <div class="field-body">
            <div class="field">
                <div class="control">
                    <div class="buttons is-centered">
                        <button class="button is-link is-large trigger-details--alert-definition--add-button">
                            <span class="icon is-small">
                                <i class="fas fa-plus-circle"></i>
                            </span>
                            <span>New alert definition</span>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>