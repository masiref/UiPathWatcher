export const strings = {
    addForm: 'add-form'
};

export const selectors = {
    table: 'table.robot-tools',
    formsSection: '.forms-section',
    addFormSection: '.add-form-section',
    addForm: '#add-form',
    editFormSection: '.edit-form-section',
    editForm: '#edit-form',
    labelInput: 'input.label_',
    processNameInput: 'input.process-name',
    colorSelect: 'select.color',
    createButton: 'button.create',
    createButtonChildren: 'button.create *',
    resetButton: 'button.reset',
    resetButtonChildren: 'button.reset *',
    saveButton: 'button.save',
    saveButtonChildren: 'button.save *',
    cancelButton: 'button.cancel',
    cancelButtonChildren: 'button.cancel *',
    removeButton: 'button.remove',
    removeButtonChildren: 'button.remove *'
};

export const elements = {
    table: document.querySelector(selectors.table),
    formsSection: document.querySelector(selectors.formsSection),
    addForm: document.querySelector(selectors.addForm)
};