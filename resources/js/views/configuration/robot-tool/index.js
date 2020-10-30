import toastr from 'toastr';

import Configuration from '../../../models/Configuration';
import RobotTool from '../../../models/RobotTool';

import * as _base from '../../base';
import * as base from './base';
import * as view from './view';

import * as layoutController from '../../layout/index';

const configuration = new Configuration('configuration.robot-tool.index');

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
                    toastr.success('Extension successfully added!', null, {
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
        console.log(`Unable to init extension controller: ${error}`);
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

        const robotTool = new RobotTool(id);

        robotTool.loadEditForm().then(response => {
            view.updateEditFormSection(robotTool.editForm);

            const form = document.querySelector(base.selectors.editForm);
            form.addEventListener('keyup', checkForm);
            form.addEventListener('change', checkForm);

            form.addEventListener('click', async (e) => {
                const saveButton = form.querySelector(base.selectors.saveButton);
                if (e.target.matches(`${base.selectors.saveButton}, ${base.selectors.saveButtonChildren}`) && !saveButton.disabled) {
                    update().then(response => {
                        loadAddForm(e);
                        updateTable();
                        toastr.success('Extension successfully updated!', null, {
                            positionClass: 'toast-bottom-left'
                        });
                    });
                }
                if (e.target.matches(`${base.selectors.cancelButton}, ${base.selectors.cancelButtonChildren}`)) {
                    loadAddForm(e);
                }
                if (e.target.matches(`${base.selectors.removeButton}, ${base.selectors.removeButtonChildren}`)) {
                    _base.swalWithBulmaButtons.fire({
                        title: 'Extension removal confirmation',
                        text: 'This extension will be removed. Are you sure?',
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
                                toastr.success('Extension successfully removed!', null, {
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
    
    const labelInput = form.querySelector(base.selectors.labelInput);
    const processNameInput = form.querySelector(base.selectors.processNameInput);
    const colorSelect = form.querySelector(base.selectors.colorSelect);

    const labelInputValid = !(labelInput.value.trim() === '');
    _base.toggleSuccessDangerState(labelInput, labelInputValid);

    const processNameInputValid = !(processNameInput.value.trim() === '');
    _base.toggleSuccessDangerState(processNameInput, processNameInputValid);

    const colorSelectValid = (colorSelect.value !== 'none');
    _base.toggleSuccessDangerState(colorSelect.parentNode, colorSelectValid);
    
    const formValid = labelInputValid && processNameInputValid && colorSelectValid;

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

        const labelInput = form.querySelector(base.selectors.labelInput);
        const processNameInput = form.querySelector(base.selectors.processNameInput);
        const colorSelect = form.querySelector(base.selectors.colorSelect);
        
        return new Promise((resolve, reject) => {
            const robotTool = new RobotTool();
            resolve(
                robotTool.save(
                    labelInput.value.trim(),
                    processNameInput.value.trim(),
                    colorSelect.value
                ).then(res => {
                    resetForm();
                    form.querySelector(base.selectors.createButton).disabled = true;
                    _base.clearLoader(form);
                })
            )
        });
    } catch (error) {
        toastr.error(`Extension not added due to application exception: ${error}`, null, {
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

        const labelInput = form.querySelector(base.selectors.labelInput);
        const processNameInput = form.querySelector(base.selectors.processNameInput);
        const colorSelect = form.querySelector(base.selectors.colorSelect);
        
        return new Promise((resolve, reject) => {
            const robotTool = new RobotTool(form.dataset.id);
            resolve(
                robotTool.update(
                    labelInput.value.trim(),
                    processNameInput.value.trim(),
                    colorSelect.value
                ).then(response => {
                    resetForm();
                    _base.clearLoader(form);
                })
            )
        });
    } catch (error) {
        toastr.error(`Extension not updated due to application exception: ${error}`, null, {
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
            const robotTool = new RobotTool(form.dataset.id);
            resolve(
                robotTool.remove().then(response => {
                    resetForm();
                    _base.clearLoader(form);
                })
            )
        });
    } catch (error) {
        toastr.error(`Extension not removed due to application exception: ${error}`, null, {
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
                configuration.updateRobotToolsTable().then(res => {
                    view.updateTable(configuration.robotToolsTable);
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

        const labelInput = form.querySelector(base.selectors.labelInput);
        const processNameInput = form.querySelector(base.selectors.processNameInput);
        const colorSelect = form.querySelector(base.selectors.colorSelect);
        
        form.reset();
        _base.removeStates(labelInput);
        _base.removeStates(processNameInput);
        _base.removeStates(colorSelect.parentNode);
    } catch (error) {
        console.log(error);
    }
};