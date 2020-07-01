import toastr from 'toastr';

import Configuration from '../../../models/Configuration';
import Client from '../../../models/Client';

import * as _base from '../../base';
import * as base from './base';
import * as view from './view';

import * as layoutController from '../../layout/index';

const configuration = new Configuration('configuration.client.index');

let currentMode = 'add';

export const init = () => {
    try {
        setInterval(() => {
            layoutController.update(configuration.layout);
        }, 45000);

        $(base.selectors.table).DataTable()
            .on('select', loadEditForm);
        
        base.elements.addForm.addEventListener('keyup', checkForm);
        base.elements.addForm.addEventListener('change', checkForm);

        base.elements.addForm.addEventListener('click', async (e) => {
            const createButton = base.elements.addForm.querySelector(base.selectors.createButton);
            if (e.target.matches(`${base.selectors.createButton}, ${base.selectors.createButtonChildren}`) && !createButton.disabled) {
                create().then(res => {
                    toastr.success('Customer successfully added!', null, {
                        positionClass: 'toast-bottom-left'
                    });
                    return Promise.all([
                        updateTable(),
                        layoutController.update(configuration.layout)
                    ]);
                });
            }
            if (e.target.matches(`${base.selectors.resetButton}, ${base.selectors.resetButtonChildren}`)) {
                resetForm();
                createButton.disabled = true;
            }
        });
    } catch (error) {
        console.log(`Unable to init client controller: ${error}`);
    }
};

const loadAddForm = e => {
    try {
        currentMode = 'add';
        
        $(base.selectors.table).DataTable().rows().deselect();
        view.showAddForm();
    } catch (error) {
        console.log(error);
    }
};

const loadEditForm = e => {
    try {    
        currentMode = 'edit';

        _base.renderLoader(document.querySelector(base.selectors.table));
        _base.renderLoader(base.elements.formsSection);

        const row = $(base.selectors.table).DataTable().row({ selected: true }).node();
        const id = row.dataset.id;

        const client = new Client(id);

        client.loadEditForm().then(response => {
            view.updateEditFormSection(client.editForm);

            const form = document.querySelector(base.selectors.editForm);
            form.addEventListener('keyup', checkForm);
            form.addEventListener('change', checkForm);

            form.addEventListener('click', async (e) => {
                const saveButton = form.querySelector(base.selectors.saveButton);
                if (e.target.matches(`${base.selectors.saveButton}, ${base.selectors.saveButtonChildren}`) && !saveButton.disabled) {
                    update().then(response => {
                        loadAddForm(e);
                        updateTable();
                        layoutController.update(configuration.layout);
                        toastr.success('Customer successfully updated!', null, {
                            positionClass: 'toast-bottom-left'
                        });
                    });
                }
                if (e.target.matches(`${base.selectors.cancelButton}, ${base.selectors.cancelButtonChildren}`)) {
                    loadAddForm(e);
                }
                if (e.target.matches(`${base.selectors.removeButton}, ${base.selectors.removeButtonChildren}`)) {
                    _base.swalWithBulmaButtons.fire({
                        title: 'Customer removal confirmation',
                        text: 'This customer and all its related elements (watched processes, alert triggers and alerts) will be removed. Are you sure?',
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonText: '<span class="icon"><i class="fas fa-trash-alt"></i></span><span>Remove it!</span>',
                        cancelButtonText: '<span class="icon"><i class="fas fa-undo"></i></span><span>Undo</span>'
                    }).then(result => {
                        if (result.value) {
                            remove().then(reponse => {
                                loadAddForm(e);
                                updateTable();
                                layoutController.update(configuration.layout);
                                toastr.success('Customer successfully removed!', null, {
                                    positionClass: 'toast-bottom-left'
                                });
                            });
                        }
                    });
                }
            });

            view.showEditForm();

            _base.clearLoader(document.querySelector(base.selectors.table));
            _base.clearLoader(base.elements.formsSection);

            checkForm(e);
        });
    } catch (error) {
        console.log(error);
        _base.clearLoader(document.querySelector(base.selectors.table));
        _base.clearLoader(base.elements.formsSection);
    }
};

const checkForm = e => {
    const form = (currentMode === 'add' ? base.elements.addForm : document.querySelector(base.selectors.editForm));

    const nameInput = form.querySelector(base.selectors.nameInput);
    const codeInput = form.querySelector(base.selectors.codeInput);
    const orchestratorSelect = form.querySelector(base.selectors.orchestratorSelect);
    const orchestratorTenantInput = form.querySelector(base.selectors.orchestratorTenantInput);
    const orchestratorApiUserUsernameInput = form.querySelector(base.selectors.orchestratorApiUserUsernameInput);
    const orchestratorApiUserPasswordInput = form.querySelector(base.selectors.orchestratorApiUserPasswordInput);
    const elasticSearchUrlInput = form.querySelector(base.selectors.elasticSearchUrlInput);
    const elasticSearchIndexInput = form.querySelector(base.selectors.elasticSearchIndexInput);
    const elasticSearchApiUserUsernameInput = form.querySelector(base.selectors.elasticSearchApiUserUsernameInput);
    const elasticSearchApiUserPasswordInput = form.querySelector(base.selectors.elasticSearchApiUserPasswordInput);

    const nameInputValid = !(nameInput.value.trim() === '');
    _base.toggleSuccessDangerState(nameInput, nameInputValid);

    const codeInputValid = !(codeInput.value.trim() === '');
    _base.toggleSuccessDangerState(codeInput, codeInputValid);
    
    const orchestratorSelectValid = !(orchestratorSelect.value === "0");
    _base.toggleSuccessDangerState(orchestratorSelect.parentNode, orchestratorSelectValid);

    const orchestratorTenantInputValid = !(orchestratorTenantInput.value.trim() === '');
    _base.toggleSuccessDangerState(orchestratorTenantInput, orchestratorTenantInputValid);

    const orchestratorApiUserUsernameInputValid = !(orchestratorApiUserUsernameInput.value.trim() === '');
    _base.toggleSuccessDangerState(orchestratorApiUserUsernameInput, orchestratorApiUserUsernameInputValid);

    const orchestratorApiUserPasswordInputValid = !(orchestratorApiUserPasswordInput.value.trim() === '');
    _base.toggleSuccessDangerState(orchestratorApiUserPasswordInput, orchestratorApiUserPasswordInputValid);

    const elasticSearchUrlInputValid = !(elasticSearchUrlInput.value.trim() === '' || !_base.validURL(elasticSearchUrlInput.value));
    _base.toggleSuccessDangerState(elasticSearchUrlInput, elasticSearchUrlInputValid);

    const elasticSearchIndexInputValid = !(elasticSearchIndexInput.value.trim() === '');
    _base.toggleSuccessDangerState(elasticSearchIndexInput, elasticSearchIndexInputValid);

    _base.toggleSuccessDangerState(elasticSearchApiUserUsernameInput, true);
    _base.toggleSuccessDangerState(elasticSearchApiUserPasswordInput, true);
    
    const formValid = nameInputValid && codeInputValid && orchestratorSelectValid
         && orchestratorTenantInputValid && orchestratorApiUserUsernameInputValid
         && orchestratorApiUserPasswordInputValid
         && elasticSearchUrlInputValid && elasticSearchIndexInputValid;

    if (currentMode === 'add') {
        form.querySelector(base.selectors.createButton).disabled = !formValid;
    } else {
        form.querySelector(base.selectors.saveButton).disabled = !formValid;
    }
};

const create = async () => {
    const form = base.elements.addForm;
    try {
        _base.renderLoader(form);

        const nameInput = form.querySelector(base.selectors.nameInput);
        const codeInput = form.querySelector(base.selectors.codeInput);
        const orchestratorSelect = form.querySelector(base.selectors.orchestratorSelect);
        const orchestratorTenantInput = form.querySelector(base.selectors.orchestratorTenantInput);
        const orchestratorApiUserUsernameInput = form.querySelector(base.selectors.orchestratorApiUserUsernameInput);
        const orchestratorApiUserPasswordInput = form.querySelector(base.selectors.orchestratorApiUserPasswordInput);
        const elasticSearchUrlInput = form.querySelector(base.selectors.elasticSearchUrlInput);
        const elasticSearchIndexInput = form.querySelector(base.selectors.elasticSearchIndexInput);
        const elasticSearchApiUserUsernameInput = form.querySelector(base.selectors.elasticSearchApiUserUsernameInput);
        const elasticSearchApiUserPasswordInput = form.querySelector(base.selectors.elasticSearchApiUserPasswordInput);

        return new Promise((resolve, reject) => {
            const client = new Client();
            resolve(
                client.save(
                    nameInput.value.trim(),
                    codeInput.value.trim(),
                    orchestratorSelect.value.trim(),
                    orchestratorTenantInput.value.trim(),
                    orchestratorApiUserUsernameInput.value.trim(),
                    orchestratorApiUserPasswordInput.value,
                    elasticSearchUrlInput.value.trim(),
                    elasticSearchIndexInput.value.trim(),
                    elasticSearchApiUserUsernameInput.value.trim(),
                    elasticSearchApiUserPasswordInput.value
                ).then(res => {
                    resetForm();
                    _base.clearLoader(form);
                })
            )
        });
    } catch (error) {
        toastr.error(`Customer not added due to application exception: ${error}`, null, {
            positionClass: 'toast-bottom-left'
        });
        console.log(error);
        _base.clearLoader(form);
    }
};

const update = async () => {
    const form = document.querySelector(base.selectors.editForm);
    try {
        _base.renderLoader(form);

        const nameInput = form.querySelector(base.selectors.nameInput);
        const codeInput = form.querySelector(base.selectors.codeInput);
        const orchestratorSelect = form.querySelector(base.selectors.orchestratorSelect);
        const orchestratorTenantInput = form.querySelector(base.selectors.orchestratorTenantInput);
        const orchestratorApiUserUsernameInput = form.querySelector(base.selectors.orchestratorApiUserUsernameInput);
        const orchestratorApiUserPasswordInput = form.querySelector(base.selectors.orchestratorApiUserPasswordInput);
        const elasticSearchUrlInput = form.querySelector(base.selectors.elasticSearchUrlInput);
        const elasticSearchIndexInput = form.querySelector(base.selectors.elasticSearchIndexInput);
        const elasticSearchApiUserUsernameInput = form.querySelector(base.selectors.elasticSearchApiUserUsernameInput);
        const elasticSearchApiUserPasswordInput = form.querySelector(base.selectors.elasticSearchApiUserPasswordInput);
        
        return new Promise((resolve, reject) => {
            const client = new Client(form.dataset.id);
            resolve(
                client.update(
                    nameInput.value.trim(),
                    codeInput.value.trim(),
                    orchestratorSelect.value.trim(),
                    orchestratorTenantInput.value.trim(),
                    orchestratorApiUserUsernameInput.value.trim(),
                    orchestratorApiUserPasswordInput.value,
                    elasticSearchUrlInput.value.trim(),
                    elasticSearchIndexInput.value.trim(),
                    elasticSearchApiUserUsernameInput.value.trim(),
                    elasticSearchApiUserPasswordInput.value
                ).then(response => {
                    resetForm();
                    _base.clearLoader(form);
                })
            )
        });
    } catch (error) {
        toastr.error(`Customer not updated due to application exception: ${error}`, null, {
            positionClass: 'toast-bottom-left'
        });
        console.log(error);
        _base.clearLoader(form);
    }
};

const remove = async () => {
    const form = document.querySelector(base.selectors.editForm);
    try {
        _base.renderLoader(form);

        return new Promise((resolve, reject) => {
            const client = new Client(form.dataset.id);
            resolve(
                client.remove().then(response => {
                    resetForm();
                    _base.clearLoader(form);
                })
            )
        });
    } catch (error) {
        toastr.error(`Customer not removed due to application exception: ${error}`, null, {
            positionClass: 'toast-bottom-left'
        });
        console.log(error);
        _base.clearLoader(form);
    }
};

const updateTable = async () => {
    const table = document.querySelector(base.selectors.table);
    try {
        _base.renderLoader(table);
        return new Promise((resolve, reject) => {
            resolve(
                configuration.updateClientsTable().then(res => {
                    view.updateTable(configuration.clientsTable);
                    $(base.selectors.table).DataTable().on('select', loadEditForm);
                    _base.clearLoader(table);
                })
            )
        });
    } catch (error) {
        console.log(error);
        _base.clearLoader(table);
    }
};

const resetForm = () => {
    try {
        const form = (currentMode === 'add' ? document.querySelector(base.selectors.addForm) : document.querySelector(base.selectors.editForm));

        const nameInput = form.querySelector(base.selectors.nameInput);
        const codeInput = form.querySelector(base.selectors.codeInput);
        const orchestratorSelect = form.querySelector(base.selectors.orchestratorSelect);
        const orchestratorTenantInput = form.querySelector(base.selectors.orchestratorTenantInput);
        const orchestratorApiUserUsernameInput = form.querySelector(base.selectors.orchestratorApiUserUsernameInput);
        const orchestratorApiUserPasswordInput = form.querySelector(base.selectors.orchestratorApiUserPasswordInput);
        const elasticSearchUrlInput = form.querySelector(base.selectors.elasticSearchUrlInput);
        const elasticSearchIndexInput = form.querySelector(base.selectors.elasticSearchIndexInput);
        const elasticSearchApiUserUsernameInput = form.querySelector(base.selectors.elasticSearchApiUserUsernameInput);
        const elasticSearchApiUserPasswordInput = form.querySelector(base.selectors.elasticSearchApiUserPasswordInput);

        form.reset();
        _base.removeStates(nameInput);
        _base.removeStates(codeInput);
        _base.removeStates(orchestratorSelect.parentNode);
        _base.removeStates(orchestratorTenantInput);
        _base.removeStates(orchestratorApiUserUsernameInput);
        _base.removeStates(orchestratorApiUserPasswordInput);
        _base.removeStates(elasticSearchUrlInput);
        _base.removeStates(elasticSearchIndexInput);
        _base.removeStates(elasticSearchApiUserUsernameInput);
        _base.removeStates(elasticSearchApiUserPasswordInput);
    } catch (error) {
        console.log(error);
    }
};