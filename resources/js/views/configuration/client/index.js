import toastr from 'toastr';

import Configuration from '../../../models/Configuration';
import Client from '../../../models/Client';

import * as _base from '../../base';
import * as base from './base';
import * as view from './view';

import * as layoutController from '../../layout/index';

const configuration = new Configuration('configuration.client.index');

const nameInput = document.querySelector(base.selectors.nameInput);
const codeInput = document.querySelector(base.selectors.codeInput);
const orchestratorSelect = document.querySelector(base.selectors.orchestratorSelect);
const createButton = base.elements.createButton;

export const init = () => {
    try {
        setInterval(() => {
            layoutController.update(configuration.layout);
        }, 45000);
        
        base.elements.addForm.addEventListener('keyup', checkForm);
        base.elements.addForm.addEventListener('change', checkForm);

        base.elements.addForm.addEventListener('click', async (e) => {
            if (e.target.matches(`${base.selectors.createButton}, ${base.selectors.createButtonChildren}`) && !createButton.disabled) {
                create().then(res => {
                    toastr.success('Client successfully added!', null, {
                        positionClass: 'toast-bottom-right'
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

const checkForm = e => {
    const nameInputValid = !(nameInput.value.trim() === '');
    _base.toggleSuccessDangerState(nameInput, nameInputValid);

    const codeInputValid = !(codeInput.value.trim() === '');
    _base.toggleSuccessDangerState(codeInput, codeInputValid);
    
    const orchestratorSelectValid = !(orchestratorSelect.value === "0");
    _base.toggleSuccessDangerState(orchestratorSelect.parentNode, orchestratorSelectValid);
    
    const formValid = nameInputValid && codeInputValid && orchestratorSelectValid;

    createButton.disabled = !formValid;
};

const create = async () => {
    const addForm = base.elements.addForm;
    try {
        _base.renderLoader(addForm);
        return new Promise((resolve, reject) => {
            const client = new Client();
            resolve(
                client.save(
                    nameInput.value.trim(),
                    codeInput.value.trim(),
                    orchestratorSelect.value.trim()
                ).then(res => {
                    resetForm();
                    _base.clearLoader(addForm);
                })
            )
        });
    } catch (error) {
        toastr.error(`Client not added due to application exception: ${error}`, null, {
            positionClass: 'toast-bottom-right'
        });
        console.log(error);
        _base.clearLoader(addForm);
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
        _base.removeStates(orchestratorSelect.parentNode);
    } catch (error) {
        console.log(error);
    }
};