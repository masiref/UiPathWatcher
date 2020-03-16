import toastr from 'toastr';

import Configuration from '../../models/Configuration';
import UiPathOrchestrator from '../../models/UiPathOrchestrator';

import * as _base from '../base';
import * as base from './base';
import * as view from './view';

import * as layoutController from '../layout/index';

const configuration = new Configuration();

const nameInput = document.querySelector(base.selectors.nameInput);
const urlInput = document.querySelector(base.selectors.urlInput);
const tenantInput = document.querySelector(base.selectors.tenantInput);
const apiUserUsernameInput = document.querySelector(base.selectors.apiUserUsernameInput);
const apiUserPasswordInput = document.querySelector(base.selectors.apiUserPasswordInput);
const kibanaUrlInput = document.querySelector(base.selectors.kibanaUrlInput);
const kibanaIndexInput = document.querySelector(base.selectors.kibanaIndexInput);
const createButton = base.elements.createButton;

export const init = () => {
    try {
        base.elements.addForm.addEventListener('keyup', e => {
            let nameInputValid = false;
            let urlInputValid = false;
            let tenantInputValid = false;
            let apiUserUsernameInputValid = false;
            let apiUserPasswordInputValid = false;
            let kibanaUrlInputValid = false;
            let kibanaIndexInputValid = false;

            if (nameInput.value.trim() === '') {
                nameInput.classList.remove('is-success');
                nameInput.classList.add('is-danger');
            } else {
                nameInput.classList.add('is-success');
                nameInput.classList.remove('is-danger');
                nameInputValid = true;
            }
            
            if (urlInput.value.trim() === '' || !_base.validURL(urlInput.value)) {
                urlInput.classList.remove('is-success');
                urlInput.classList.add('is-danger');
            } else {
                urlInput.classList.add('is-success');
                urlInput.classList.remove('is-danger');
                urlInputValid = true;
            }

            if (tenantInput.value.trim() === '') {
                tenantInput.classList.remove('is-success');
                tenantInput.classList.add('is-danger');
            } else {
                tenantInput.classList.add('is-success');
                tenantInput.classList.remove('is-danger');
                tenantInputValid = true;
            }

            if (apiUserUsernameInput.value.trim() === '') {
                apiUserUsernameInput.classList.remove('is-success');
                apiUserUsernameInput.classList.add('is-danger');
            } else {
                apiUserUsernameInput.classList.add('is-success');
                apiUserUsernameInput.classList.remove('is-danger');
                apiUserUsernameInputValid = true;
            }

            if (apiUserPasswordInput.value.trim() === '') {
                apiUserPasswordInput.classList.remove('is-success');
                apiUserPasswordInput.classList.add('is-danger');
            } else {
                apiUserPasswordInput.classList.add('is-success');
                apiUserPasswordInput.classList.remove('is-danger');
                apiUserPasswordInputValid = true;
            }
            
            if (kibanaUrlInput.value.trim() === '' || !_base.validURL(kibanaUrlInput.value)) {
                kibanaUrlInput.classList.remove('is-success');
                kibanaUrlInput.classList.add('is-danger');
            } else {
                kibanaUrlInput.classList.add('is-success');
                kibanaUrlInput.classList.remove('is-danger');
                kibanaUrlInputValid = true;
            }

            if (kibanaIndexInput.value.trim() === '') {
                kibanaIndexInput.classList.remove('is-success');
                kibanaIndexInput.classList.add('is-danger');
            } else {
                kibanaIndexInput.classList.add('is-success');
                kibanaIndexInput.classList.remove('is-danger');
                kibanaIndexInputValid = true;
            }
            
            const formValid = nameInputValid && urlInputValid && tenantInputValid && apiUserUsernameInputValid &&
                apiUserPasswordInputValid && kibanaUrlInputValid && kibanaIndexInputValid;

            createButton.disabled = !formValid;
        });

        base.elements.addForm.addEventListener('click', async (e) => {
            if (e.target.matches(`${base.selectors.createButton}, ${base.selectors.createButtonChildren}`)) {
                create().then(res => {
                    toastr.success('Orchestrator successfully added!', null, {
                        positionClass: 'toast-bottom-center'
                    });
                    return Promise.all([
                        updateTable(),
                        layoutController.update(configuration.layout)
                    ]);
                });
            }
        });
    } catch (error) {
        console.log(`Unable to init uipath orchestrator controller: ${error}`);
    }
};

const create = async () => {
    const addForm = base.elements.addForm;
    try {
        _base.renderLoader(addForm);
        return new Promise((resolve, reject) => {
            const orchestrator = new UiPathOrchestrator();
            resolve(
                orchestrator.save(
                    nameInput.value.trim(),
                    urlInput.value.trim(),
                    tenantInput.value.trim(),
                    apiUserUsernameInput.value.trim(),
                    apiUserUsernameInput.value,
                    kibanaUrlInput.value.trim(),
                    kibanaIndexInput.value.trim()
                ).then(res => {
                    resetForm();
                    _base.clearLoader(addForm);
                })
            )
        });
    } catch (error) {
        toastr.error(`Orchestrator not added due to application exception: ${error}`, null, {
            positionClass: 'toast-bottom-center'
        });
        console.log(error);
        _base.clearLoader(addForm);
    }
};

const updateTable = async () => {
    const table = base.elements.table;
    try {
        _base.renderLoader(table);
        return new Promise((resolve, reject) => {
            resolve(
                configuration.updateOrchestratorsTable().then(res => {
                    view.updateTable(configuration.orchestratorsTable);
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
        base.elements.addForm.reset();
        nameInput.classList.remove('is-success');
        urlInput.classList.remove('is-success');
        tenantInput.classList.remove('is-success');
        apiUserUsernameInput.classList.remove('is-success');
        apiUserPasswordInput.classList.remove('is-success');
        kibanaUrlInput.classList.remove('is-success');
        kibanaIndexInput.classList.remove('is-success');
    } catch (error) {
        console.log(error);
    }
};