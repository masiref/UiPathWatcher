export const strings = {
    addForm: 'add-form'
};

export const selectors = {
    table: 'table.clients',
    formsSection: '.forms-section',
    addFormSection: '.add-form-section',
    addForm: '#add-form',
    editFormSection: '.edit-form-section',
    editForm: '#edit-form',
    nameInput: 'input.name',
    codeInput: 'input.code',
    orchestratorSelect: 'select.orchestrator',
    orchestratorTenantInput: 'input.orchestrator-tenant',
    orchestratorApiUserUsernameInput: 'input.orchestrator-api-user-username',
    orchestratorApiUserPasswordInput: 'input.orchestrator-api-user-password',
    elasticSearchUrlInput: 'input.elastic-search-url',
    elasticSearchIndexInput: 'input.elastic-search-index',
    elasticSearchApiUserUsernameInput: 'input.elastic-search-api-user-username',
    elasticSearchApiUserPasswordInput: 'input.elastic-search-api-user-password',
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