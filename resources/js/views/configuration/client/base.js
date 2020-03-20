export const strings = {
    addForm: 'add-form'
};

export const selectors = {
    table: 'table.clients',
    addForm: '#add-form',
    nameInput: 'input#name',
    codeInput: 'input#code',
    orchestratorSelect: 'select#orchestrator',
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