<div class="edit-form-section" style="display: none;">
    @include('layouts.title', [
        'title' => 'Configure an existing alert trigger',
        'icon' => 'edit',
        'color' => 'primary'
    ])
    <form id="edit-form" data-id="{{ ($alertTrigger ?? false) ? $alertTrigger->id : '' }}" action="#" onsubmit="return false;">
        <div class="steps" id="alert-trigger-creation-steps">
            @include('configuration.alert-trigger.steps.items')
            @include('configuration.alert-trigger.steps.content.index')
            @include('configuration.alert-trigger.steps.actions')
        </div>

        <div class="is-divider"></div>
        <div class="edit-buttons-section">
            @include('configuration.alert-trigger.form.edit-buttons')
        </div>
    </form>
</div>