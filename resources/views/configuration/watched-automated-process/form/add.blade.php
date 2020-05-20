<div class="add-form-section">
    @include('layouts.title', [
        'title' => 'Watch a new process',
        'icon' => 'plus-circle',
        'color' => 'primary'
    ])

    <form id="add-form" action="#" onsubmit="return false;">
        @include('configuration.watched-automated-process.form.controls')

        <div class="is-divider"></div>

        <div class="field is-horizontal">
            <div class="field-body">
                <div class="field">
                    <div class="control">
                        <div class="buttons is-right">
                            <button class="button is-primary create" disabled>
                                <span class="icon is-small">
                                    <i class="fas fa-plus-circle"></i>
                                </span>
                                <span>Create</span>
                            </button>
                            <button class="button is-danger is-outlined reset">
                                <span class="icon is-small">
                                    <i class="fas fa-undo"></i>
                                </span>
                                <span>Reset</span>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>