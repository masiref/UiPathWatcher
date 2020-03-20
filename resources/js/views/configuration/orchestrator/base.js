export const strings = {
    addForm: 'add-form'
};

export const selectors = {
    table: 'table.orchestrators',
    addForm: '#add-form',
    nameInput: 'input#name',
    codeInput: 'input#code',
    urlInput: 'input#url',
    tenantInput: 'input#tenant',
    apiUserUsernameInput: 'input#api-user-username',
    apiUserPasswordInput: 'input#api-user-password',
    kibanaUrlInput: 'input#kibana-url',
    kibanaIndexInput: 'input#kibana-index',
    createButton: 'button.create',
    createButtonChildren: 'button.create *',
    resetButton: 'button.reset',
    resetButtonChildren: 'button.reset *'
};

export const elements = {
    table: document.querySelector(selectors.table),
    addForm: document.querySelector(selectors.addForm),
    createButton: document.querySelector(selectors.createButton),
    resetButton: document.querySelector(selectors.resetButton)
};