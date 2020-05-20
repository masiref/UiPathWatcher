import toastr from 'toastr';

import Configuration from '../../../models/Configuration';
import Orchestrator from '../../../models/Orchestrator';

import * as _base from '../../base';
import * as base from './base';
import * as view from './view';

import * as layoutController from '../../layout/index';

const configuration = new Configuration('configuration.orchestrator.index');

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
                    toastr.success('Orchestrator successfully added!', null, {
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
        console.log(`Unable to init orchestrator controller: ${error}`);
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

        const orchestrator = new Orchestrator(id);

        orchestrator.loadEditForm().then(response => {
            view.updateEditFormSection(orchestrator.editForm);

            const form = document.querySelector(base.selectors.editForm);
            form.addEventListener('keyup', checkForm);
            form.addEventListener('change', checkForm);

            form.addEventListener('click', async (e) => {
                const saveButton = form.querySelector(base.selectors.saveButton);
                if (e.target.matches(`${base.selectors.saveButton}, ${base.selectors.saveButtonChildren}`) && !saveButton.disabled) {
                    update().then(response => {
                        loadAddForm(e);
                        updateTable();
                        toastr.success('Orchestrator successfully updated!', null, {
                            positionClass: 'toast-bottom-left'
                        });
                    });
                }
                if (e.target.matches(`${base.selectors.cancelButton}, ${base.selectors.cancelButtonChildren}`)) {
                    loadAddForm(e);
                }
                if (e.target.matches(`${base.selectors.removeButton}, ${base.selectors.removeButtonChildren}`)) {
                    _base.swalWithBulmaButtons.fire({
                        title: 'Orchestrator removal confirmation',
                        text: 'This orchestrator and all its related elements (clients, watched processes, alert triggers and alerts) will be removed. Are you sure?',
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonText: '<span class="icon"><i class="fas fa-trash-alt"></i></span><span>Remove it!</span>',
                        cancelButtonText: '<span class="icon"><i class="fas fa-undo"></i></span><span>Undo</span>'
                    }).then(result => {
                        if (result.value) {
                            remove().then(reponse => {
                                loadAddForm(e);
                                updateTable();
                                toastr.success('Orchestrator successfully removed!', null, {
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
    const urlInput = form.querySelector(base.selectors.urlInput);
    const tenantInput = form.querySelector(base.selectors.tenantInput);
    const apiUserUsernameInput = form.querySelector(base.selectors.apiUserUsernameInput);
    const apiUserPasswordInput = form.querySelector(base.selectors.apiUserPasswordInput);

    const nameInputValid = !(nameInput.value.trim() === '');
    _base.toggleSuccessDangerState(nameInput, nameInputValid);

    const codeInputValid = !(codeInput.value.trim() === '');
    _base.toggleSuccessDangerState(codeInput, codeInputValid);

    const urlInputValid = !(urlInput.value.trim() === '' || !_base.validURL(urlInput.value));
    _base.toggleSuccessDangerState(urlInput, urlInputValid);

    const tenantInputValid = !(tenantInput.value.trim() === '');
    _base.toggleSuccessDangerState(tenantInput, tenantInputValid);

    const apiUserUsernameInputValid = !(apiUserUsernameInput.value.trim() === '');
    _base.toggleSuccessDangerState(apiUserUsernameInput, apiUserUsernameInputValid);

    const apiUserPasswordInputValid = !(apiUserPasswordInput.value.trim() === '');
    _base.toggleSuccessDangerState(apiUserPasswordInput, apiUserPasswordInputValid);
    
    const formValid = nameInputValid && codeInputValid && urlInputValid && tenantInputValid && apiUserUsernameInputValid &&
        apiUserPasswordInputValid;

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
        const urlInput = form.querySelector(base.selectors.urlInput);
        const tenantInput = form.querySelector(base.selectors.tenantInput);
        const apiUserUsernameInput = form.querySelector(base.selectors.apiUserUsernameInput);
        const apiUserPasswordInput = form.querySelector(base.selectors.apiUserPasswordInput);
        
        return new Promise((resolve, reject) => {
            const orchestrator = new Orchestrator();
            resolve(
                orchestrator.save(
                    nameInput.value.trim(),
                    codeInput.value.trim(),
                    urlInput.value.trim(),
                    tenantInput.value.trim(),
                    apiUserUsernameInput.value.trim(),
                    apiUserPasswordInput.value
                ).then(res => {
                    resetForm();
                    _base.clearLoader(form);
                })
            )
        });
    } catch (error) {
        toastr.error(`Orchestrator not added due to application exception: ${error}`, null, {
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
        const urlInput = form.querySelector(base.selectors.urlInput);
        const tenantInput = form.querySelector(base.selectors.tenantInput);
        const apiUserUsernameInput = form.querySelector(base.selectors.apiUserUsernameInput);
        const apiUserPasswordInput = form.querySelector(base.selectors.apiUserPasswordInput);
        
        return new Promise((resolve, reject) => {
            const orchestrator = new Orchestrator(form.dataset.id);
            resolve(
                orchestrator.update(
                    nameInput.value.trim(),
                    codeInput.value.trim(),
                    urlInput.value.trim(),
                    tenantInput.value.trim(),
                    apiUserUsernameInput.value.trim(),
                    apiUserPasswordInput.value
                ).then(response => {
                    resetForm();
                    _base.clearLoader(form);
                })
            )
        });
    } catch (error) {
        toastr.error(`Orchestrator not updated due to application exception: ${error}`, null, {
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
            const orchestrator = new Orchestrator(form.dataset.id);
            resolve(
                orchestrator.remove().then(response => {
                    resetForm();
                    _base.clearLoader(form);
                })
            )
        });
    } catch (error) {
        toastr.error(`Orchestrator not removed due to application exception: ${error}`, null, {
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
                configuration.updateOrchestratorsTable().then(res => {
                    view.updateTable(configuration.orchestratorsTable);
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
        const urlInput = form.querySelector(base.selectors.urlInput);
        const tenantInput = form.querySelector(base.selectors.tenantInput);
        const apiUserUsernameInput = form.querySelector(base.selectors.apiUserUsernameInput);
        const apiUserPasswordInput = form.querySelector(base.selectors.apiUserPasswordInput);
        
        form.reset();
        _base.removeStates(nameInput);
        _base.removeStates(codeInput);
        _base.removeStates(urlInput);
        _base.removeStates(tenantInput);
        _base.removeStates(apiUserUsernameInput);
        _base.removeStates(apiUserPasswordInput);
    } catch (error) {
        console.log(error);
    }
};