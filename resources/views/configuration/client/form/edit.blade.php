<div class="edit-form-section" style="display: none;">
    @include('layouts.title', [
        'title' => 'Edit a client',
        'icon' => 'edit',
        'color' => 'primary'
    ])

    <form id="edit-form" data-id="{{ ($client ?? false) ? $client->id : '' }}" action="#" onsubmit="return false;">
        @include('configuration.client.form.controls')

        <div class="is-divider"></div>
        
        <div class="field is-horizontal">
            <div class="field-body">
                <div class="field">
                    <div class="control">
                        <div class="buttons is-right">
                            <button class="button is-primary save" disabled>
                                <span class="icon is-small">
                                    <i class="fas fa-save"></i>
                                </span>
                                <span>Save</span>
                            </button>
                            <button class="button is-danger is-outlined cancel">
                                <span class="icon is-small">
                                    <i class="fas fa-undo"></i>
                                </span>
                                <span>Cancel</span>
                            </button>
                            <button class="button is-danger remove">
                                <span class="icon is-small">
                                    <i class="fas fa-trash-alt"></i>
                                </span>
                                <span>Remove</span>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>