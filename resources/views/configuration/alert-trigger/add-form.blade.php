<form id="add-form" action="#" onsubmit="return false;">
    <div class="steps" id="alert-trigger-creation-steps">
        @include('configuration.alert-trigger.steps.items')
        @include('configuration.alert-trigger.steps.content.index')
        @include('configuration.alert-trigger.steps.actions')
    </div>
</form>