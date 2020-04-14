@php
    $count = 0;
    if ($alertTrigger ?? false) {
        $count = $alertTrigger->definitions->count();
    }
    $title = 'Alert definitions &nbsp;<span class="tag is-rounded is-primary">' . $count .'</span>';
@endphp

<div class="alert-definitions-section p-b-md">
    @include('layouts.title', [
        'title' => $title,
        'titleId' => 'alert-definitions-section-title',
        'icon' => 'marker',
        'color' => 'primary'
    ])

    <div class="alert-definitions-list">
        {{--
        <!-- cold alert definition -->
        @component('configuration.alert-trigger.steps.content.trigger-details.alert-definition.component', [
            'title' => 'Unsaved alert definition',
            'level' => 'info'
        ])
            @include('configuration.alert-trigger.steps.content.trigger-details.alert-definition.rule.failed-queue-items-percentage', [
                'title' => 'Rule n°1'
            ])
        @endcomponent
        
        <!-- normal alert definition -->
        @component('configuration.alert-trigger.steps.content.trigger-details.alert-definition.component', [
            'title' => 'Unsaved alert definition',
            'level' => 'warning'
        ])
            @include('configuration.alert-trigger.steps.content.trigger-details.alert-definition.rule.kibana-search', [
                'title' => 'Rule n°1'
            ])
            @include('configuration.alert-trigger.steps.content.trigger-details.alert-definition.rule.kibana-metric-visualization', [
                'title' => 'Rule n°2'
            ])
        @endcomponent
        
        <!-- hot alert definition -->
        @component('configuration.alert-trigger.steps.content.trigger-details.alert-definition.component', [
            'title' => 'Unsaved alert definition',
            'level' => 'danger'
        ])
            @include('configuration.alert-trigger.steps.content.trigger-details.alert-definition.rule.job-duration', [
                'title' => 'Rule n°1'
            ])
            @include('configuration.alert-trigger.steps.content.trigger-details.alert-definition.rule.faulted-jobs-percentage', [
                'title' => 'Rule n°2'
            ])
        @endcomponent
        --}}
    </div>

    <!-- add alert definition button -->
    <div class="is-divider"></div>
    <div class="field is-horizontal">
        <div class="field-body">
            <div class="field">
                <div class="control">
                    <div class="buttons is-centered">
                        <button class="button is-primary is-large trigger-details--alert-definition--add-button">
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