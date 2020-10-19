<div class="field is-horizontal">
    <div class="field-body">
        <div class="field">
            <p class="control is-expanded has-icons-left">
                <input class="input is-{{ !$description ? 'danger' : 'success' }} trigger-details--alert-definition--description-input" type="text" placeholder="Description"
                    value="{{ $description }}">
                <span class="icon is-small is-left">
                    <i class="fas fa-question-circle"></i>
                </span>
            </p>
        </div>
    </div>
</div>