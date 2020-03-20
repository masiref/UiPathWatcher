import toastr from 'toastr';

import Configuration from '../../../models/Configuration';
import Orchestrator from '../../../models/Orchestrator';

import * as _base from '../../base';
import * as base from './base';
import * as view from './view';

import * as layoutController from '../../layout/index';

const configuration = new Configuration('configuration.orchestrator.index');

const nameInput = document.querySelector(base.selectors.nameInput);
const codeInput = document.querySelector(base.selectors.codeInput);
const urlInput = document.querySelector(base.selectors.urlInput);
const tenantInput = document.querySelector(base.selectors.tenantInput);
const apiUserUsernameInput = document.querySelector(base.selectors.apiUserUsernameInput);
const apiUserPasswordInput = document.querySelector(base.selectors.apiUserPasswordInput);
const kibanaUrlInput = document.querySelector(base.selectors.kibanaUrlInput);
const kibanaIndexInput = document.querySelector(base.selectors.kibanaIndexInput);
const createButton = base.elements.createButton;
const resetButton = base.elements.resetButton;

export const init = () => {
    try {
        base.elements.addForm.addEventListener('keyup', checkForm);
        base.elements.addForm.addEventListener('change', checkForm);

        base.elements.addForm.addEventListener('click', async (e) => {
            if (e.target.matches(`${base.selectors.createButton}, ${base.selectors.createButtonChildren}`) && !createButton.disabled) {
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
            if (e.target.matches(`${base.selectors.resetButton}, ${base.selectors.resetButtonChildren}`)) {
                resetForm();
                createButton.disabled = true;
            }
        });
    } catch (error) {
        console.log(`Unable to init orchestrator controller: ${error}`);
    }
};

const checkForm = e => {
    let nameInputValid = false;
    let codeInputValid = false;
    let urlInputValid = false;
    let tenantInputValid = false;
    let apiUserUsernameInputValid = false;
    let apiUserPasswordInputValid = false;
    let kibanaUrlInputValid = false;
    let kibanaIndexInputValid = false;

    nameInputValid = !(nameInput.value.trim() === '');
    _base.toggleSuccessDangerState(nameInput, nameInputValid);

    codeInputValid = !(codeInput.value.trim() === '');
    _base.toggleSuccessDangerState(codeInput, codeInputValid);

    urlInputValid = !(urlInput.value.trim() === '' || !_base.validURL(urlInput.value));
    _base.toggleSuccessDangerState(urlInput, urlInputValid);

    tenantInputValid = !(tenantInput.value.trim() === '');
    _base.toggleSuccessDangerState(tenantInput, tenantInputValid);

    apiUserUsernameInputValid = !(apiUserUsernameInput.value.trim() === '');
    _base.toggleSuccessDangerState(apiUserUsernameInput, apiUserUsernameInputValid);

    apiUserPasswordInputValid = !(apiUserPasswordInput.value.trim() === '');
    _base.toggleSuccessDangerState(apiUserPasswordInput, apiUserPasswordInputValid);

    kibanaUrlInputValid = !(kibanaUrlInput.value.trim() === '' || !_base.validURL(kibanaUrlInput.value));
    _base.toggleSuccessDangerState(kibanaUrlInput, kibanaUrlInputValid);

    kibanaIndexInputValid = !(kibanaIndexInput.value.trim() === '');
    _base.toggleSuccessDangerState(kibanaIndexInput, kibanaIndexInputValid);
    
    const formValid = nameInputValid && codeInputValid && urlInputValid && tenantInputValid && apiUserUsernameInputValid &&
        apiUserPasswordInputValid && kibanaUrlInputValid && kibanaIndexInputValid;

    createButton.disabled = !formValid;
};

const create = async () => {
    const addForm = base.elements.addForm;
    try {
        _base.renderLoader(addForm);
        return new Promise((resolve, reject) => {
            const orchestrator = new Orchestrator();
            resolve(
                orchestrator.save(
                    nameInput.value.trim(),
                    codeInput.value.trim(),
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
        _base.removeStates(nameInput);
        _base.removeStates(codeInput);
        _base.removeStates(urlInput);
        _base.removeStates(tenantInput);
        _base.removeStates(apiUserUsernameInput);
        _base.removeStates(apiUserPasswordInput);
        _base.removeStates(kibanaUrlInput);
        _base.removeStates(kibanaIndexInput);
    } catch (error) {
        console.log(error);
    }
};