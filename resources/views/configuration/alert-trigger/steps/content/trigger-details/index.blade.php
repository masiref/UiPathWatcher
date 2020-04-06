@if ($alertTrigger ?? false)
    @include('configuration.alert-trigger.steps.content.watched-process-information')
    <div class="field-body">
        <div class="field">
            <p class="control is-expanded has-icons-left">
                <input class="input is-danger trigger-details--title-input" type="text" placeholder="Title">
                <span class="icon is-small is-left">
                    <i class="fas fa-dragon"></i>
                </span>
            </p>
        </div>
    </div>
    <div class="is-divider"></div>
    @include('configuration.alert-trigger.steps.content.trigger-details.alert-definition.index')
@endif