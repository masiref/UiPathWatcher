import toastr from 'toastr';
import bulmaSteps from 'bulma-steps';
import bulmaCalendar from 'bulma-calendar';

import Configuration from '../../../models/Configuration';
import Client from '../../../models/Client';
import WatchedAutomatedProcess from '../../../models/WatchedAutomatedProcess';
import AlertTrigger from '../../../models/AlertTrigger';
import AlertTriggerDefinition from '../../../models/AlertTriggerDefinition';
import AlertTriggerRule from '../../../models/AlertTriggerRule';

import * as _base from '../../base';
import * as base from './base';
import * as view from './view';

import * as layoutController from '../../layout/index';

const BS_TRIGGER_DETAILS_STEP_ACCESS_REFUSED_BY_USER = 'trigger-details-step-access-refused-by-user';

const bulmaStepsHiddenErrors = [
    BS_TRIGGER_DETAILS_STEP_ACCESS_REFUSED_BY_USER
];

const configuration = new Configuration('configuration.alert-trigger.index');

let currentMode = 'add';
let steps;

const processSelection = {
    clientSelect: form => {
        return form.querySelector(base.selectors.processSelection.clientSelect);
    },
    watchedProcessSelect: form => {
        return form.querySelector(base.selectors.processSelection.watchedProcessSelect);
    }
};

const details = {
    currentWatchedAutomatedProcess: new WatchedAutomatedProcess(),
    currentClient: new Client(),
    currentAlertTrigger: new AlertTrigger()
};

export const init = () => {
    try {
        setInterval(() => {
            layoutController.update(configuration.layout);
        }, 45000);

        $(base.selectors.table).DataTable()
            .on('select', loadEditForm);
        
        $(base.selectors.table).DataTable()
            .on('user-select', function (e, dt, type, cell, originalEvent) {
                if (originalEvent.target.closest('tr').classList.contains('is-selected')) {
                    e.preventDefault();
                }
            });

        attachSteps();
        
        initProcessSelection();
    } catch (error) {
        console.log(`Unable to init alert trigger controller: ${error}`);
    }
};

const attachSteps = () => {
    const form = (currentMode === 'add' ? base.elements.addForm : document.querySelector(base.selectors.editForm));
    const formSelector = (currentMode === 'add' ? base.selectors.addForm : base.selectors.editForm);

    steps = bulmaSteps.attach(`${formSelector} ${base.selectors.steps}`, {
        onShow: id => {
            switch (id) {
                case 0:
                    validateProcessSelectionForm();
                    break;
                case 1:
                    loadDefaultAlertTriggerDetails(processSelection.watchedProcessSelect(form).value);
                    break;
                case 2:
                    loadDefaultAlertTriggerSummary(processSelection.watchedProcessSelect(form).value);
                    break;
                case 3:
                    if (currentMode === 'add') {
                        create().then(response => {
                            initAlertTriggerConfirmation();
                        });
                    } else {
                        update().then(response => {
                            initAlertTriggerConfirmation();
                        });
                    }
                    break;
            }
        },
        beforeNext: id => {
            switch (id) {
                case 0:
                    return validateProcessSelectionForm();
                    break;
                case 1:
                    return validateTriggerDetailsForm();
                    break;
                case 2:
                    if (!details.currentAlertTrigger.hasChanged() && currentMode === 'edit') {
                        return [ 'There have been no changes to the selected alert trigger' ];
                    }
                    break;
            }
        },
        onError: error => {
            if (!bulmaStepsHiddenErrors.includes(error)) {
                toastr.error(error, null, {
                    positionClass: 'toast-bottom-left'
                });
            }
        }
    });
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
        if (details.currentAlertTrigger.hasChanged()) {
            _base.swalWithBulmaButtons.fire({
                title: 'Alert trigger closing confirmation',
                text: 'This alert trigger has changed, all changes will be lost. Are you sure?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: '<span class="icon"><i class="fas fa-check"></i></span><span>Yes</span>',
                cancelButtonText: '<span class="icon"><i class="fas fa-times"></i></span><span>No</span>'
            }).then(result => {
                if (result.value) {
                    if (details.currentAlertTrigger.id) {
                        $(base.selectors.table).DataTable().row(`tr[data-id="${details.currentAlertTrigger.id}"]`)
                            .node().classList.remove('is-selected');
                    }
                    initEditForm();
                } else {
                    $(base.selectors.table).DataTable().rows().deselect();
                    if (details.currentAlertTrigger.id) {
                        $(base.selectors.table).DataTable().row(`tr[data-id="${details.currentAlertTrigger.id}"]`)
                            .node().classList.add('is-selected');
                    }
                }
            });
        } else  {
            if (details.currentAlertTrigger.id) {
                $(base.selectors.table).DataTable().row(`tr[data-id="${details.currentAlertTrigger.id}"]`)
                    .node().classList.remove('is-selected');
            }
            initEditForm();
        }
    } catch (error) {
        console.log(error);
        _base.clearLoader(document.querySelector(base.selectors.table));
        _base.clearLoader(base.elements.formsSection);
    }
};

const initEditForm = () => {
    
    currentMode = 'edit';

    _base.renderLoader(document.querySelector(base.selectors.table));
    _base.renderLoader(base.elements.formsSection);

    const selectedRow = $(base.selectors.table).DataTable().row({ selected: true })
        || $(base.selectors.table).DataTable().row(`tr[data-id="${details.currentAlertTrigger.id}"]`);
    const row = selectedRow.node();
    const id = row.dataset.id;

    details.currentAlertTrigger = new AlertTrigger(id);

    details.currentAlertTrigger.loadEditForm().then(response => {           
        
        view.updateEditFormSection(details.currentAlertTrigger.editForm);
        attachSteps();

        const form = document.querySelector(base.selectors.editForm);
        const formSection = document.querySelector(base.selectors.editFormSection);
        form.addEventListener('keyup', validateTriggerDetailsForm);
        form.addEventListener('change', validateTriggerDetailsForm);

        form.addEventListener('click', async (e) => {
            if (e.target.matches(`${base.selectors.activateButton}, ${base.selectors.activateButtonChildren}`)) {
                _base.renderLoader(formSection);
                try {
                    details.currentAlertTrigger.activate().then(response => {
                        // set is active to true in table
                        const selectedRow = $(base.selectors.table).DataTable().row(`tr[data-id="${details.currentAlertTrigger.id}"]`);
                        const row = selectedRow.node();
                        let data = selectedRow.data();
                        data[4] = 'Yes';
                        selectedRow.data(data);
                        details.currentAlertTrigger.loadEditFormButtons().then(response => {
                            form.querySelector(base.selectors.editFormButtonsSection).innerHTML = details.currentAlertTrigger.editFormButtons;
                            _base.clearLoader(formSection);
                        });
                    });
                } catch (error) {
                    _base.clearLoader(formSection);
                    console.log(error);
                }
            }
            if (e.target.matches(`${base.selectors.disableButton}, ${base.selectors.disableButtonChildren}`)) {
                _base.renderLoader(formSection);
                try {
                    details.currentAlertTrigger.disable().then(response => {
                        // set is active to false in table
                        const selectedRow = $(base.selectors.table).DataTable().row(`tr[data-id="${details.currentAlertTrigger.id}"]`);
                        const row = selectedRow.node();
                        let data = selectedRow.data();
                        data[4] = 'No';
                        selectedRow.data(data);
                        details.currentAlertTrigger.loadEditFormButtons().then(response => {
                            form.querySelector(base.selectors.editFormButtonsSection).innerHTML = details.currentAlertTrigger.editFormButtons;
                            _base.clearLoader(formSection);
                        });
                    });
                } catch (error) {
                    _base.clearLoader(formSection);
                    console.log(error);
                }
            }
            if (e.target.matches(`${base.selectors.ignoreButton}, ${base.selectors.ignoreButtonChildren}`)) {
                _base.renderLoader(formSection);
                try {
                    details.currentAlertTrigger.ignore().then(response => {
                        // set ignored to true in table
                        const selectedRow = $(base.selectors.table).DataTable().row(`tr[data-id="${details.currentAlertTrigger.id}"]`);
                        const row = selectedRow.node();
                        let data = selectedRow.data();
                        data[5] = 'Yes';
                        selectedRow.data(data);
                        details.currentAlertTrigger.loadEditFormButtons().then(response => {
                            form.querySelector(base.selectors.editFormButtonsSection).innerHTML = details.currentAlertTrigger.editFormButtons;
                            _base.clearLoader(formSection);
                        });
                    });
                } catch (error) {
                    _base.clearLoader(formSection);
                    console.log(error);
                }
            }
            if (e.target.matches(`${base.selectors.acknowledgeButton}, ${base.selectors.acknowledgeButtonChildren}`)) {
                _base.renderLoader(formSection);
                try {
                    details.currentAlertTrigger.acknowledge().then(response => {
                        // set ignored to false in table
                        const selectedRow = $(base.selectors.table).DataTable().row(`tr[data-id="${details.currentAlertTrigger.id}"]`);
                        const row = selectedRow.node();
                        let data = selectedRow.data();
                        data[5] = 'No';
                        selectedRow.data(data);
                        details.currentAlertTrigger.loadEditFormButtons().then(response => {
                            form.querySelector(base.selectors.editFormButtonsSection).innerHTML = details.currentAlertTrigger.editFormButtons;
                            _base.clearLoader(formSection);
                        });
                    });
                } catch (error) {
                    _base.clearLoader(formSection);
                    console.log(error);
                }
            }
            if (e.target.matches(`${base.selectors.cancelButton}, ${base.selectors.cancelButtonChildren}`)) {
                if (details.currentAlertTrigger.hasChanged()) {
                    _base.swalWithBulmaButtons.fire({
                        title: 'Alert trigger closing confirmation',
                        text: 'This alert trigger has changed, all changes will be lost. Are you sure?',
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonText: '<span class="icon"><i class="fas fa-check"></i></span><span>Yes</span>',
                        cancelButtonText: '<span class="icon"><i class="fas fa-times"></i></span><span>No</span>'
                    }).then(result => {
                        if (result.value) {
                            details.currentAlertTrigger = new AlertTrigger();
                            resetForm();
                            validateProcessSelectionForm();
                            loadAddForm(e);
                        }
                    });
                } else {
                    details.currentAlertTrigger = new AlertTrigger();
                    resetForm();
                    validateProcessSelectionForm();
                    loadAddForm(e);
                }
            }
            if (e.target.matches(`${base.selectors.removeButton}, ${base.selectors.removeButtonChildren}`)) {
                _base.swalWithBulmaButtons.fire({
                    title: 'Alert trigger removal confirmation',
                    text: 'This alert trigger and all its related elements (alerts, definitions, rules) will be removed. Are you sure?',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: '<span class="icon"><i class="fas fa-trash-alt"></i></span><span>Remove it!</span>',
                    cancelButtonText: '<span class="icon"><i class="fas fa-undo"></i></span><span>Undo</span>'
                }).then(result => {
                    if (result.value) {
                        details.currentAlertTrigger.remove().then(reponse => {
                            details.currentAlertTrigger = new AlertTrigger();
                            loadAddForm(e);
                            updateTable();
                            layoutController.update(configuration.layout);
                            toastr.success('Alert trigger successfully removed!', null, {
                                positionClass: 'toast-bottom-left'
                            });
                        });
                    }
                });
            }
            const restoreButton = form.querySelector(base.selectors.restoreButton);
            if (e.target.matches(`${base.selectors.restoreButton}, ${base.selectors.restoreButtonChildren}`)) {
                console.log('restore');
                // reload buttons
            }
        });

        view.showEditForm();

        _base.clearLoader(document.querySelector(base.selectors.table));
        _base.clearLoader(base.elements.formsSection);

        //checkForm(e);
    });
}

const initProcessSelection = () => {
    try {
        const form = base.elements.addForm;
        processSelection.clientSelect(form).addEventListener('change', async (e) => {
            await loadProcesses(e);
            validateProcessSelectionForm();
        });
        processSelection.watchedProcessSelect(form).addEventListener('change', async (e) => {
            validateProcessSelectionForm(e);

            if (
                details.currentWatchedAutomatedProcess
                && processSelection.watchedProcessSelect(form).value !== '0'
                && details.currentWatchedAutomatedProcess.id !== processSelection.watchedProcessSelect(form).value
                && details.currentAlertTrigger.definitions.length > 0
            ) {
                const alert = await _base.swalWithBulmaButtons.fire({
                    title: 'New automated watched process selection detected',
                    text: `You selected a new automated watched process but you already defined ${details.currentAlertTrigger.definitions.length}
                    trigger${details.currentAlertTrigger.definitions.length > 1 ? 's' : ''} for process ${details.currentWatchedAutomatedProcess.data.name}
                    of customer ${details.currentClient.data.name}.
                    Select the previously watched automated process or all changes will be lost!`,
                    icon: 'warning',
                    showCancelButton: false,
                    confirmButtonText: 'I got it!'
                });
            }            
        });
    } catch (error) {
        console.log(`Unable to init process selection step: ${error}`);
    }
};

const initAlertTriggerDetails = () => {
    const form = (currentMode === 'add' ? base.elements.addForm : document.querySelector(base.selectors.editForm));
    const formSelector = (currentMode === 'add' ? base.selectors.addForm : base.selectors.editForm);

    validateTriggerDetailsForm();

    try {
        form.addEventListener('change', e => {
            details.currentAlertTrigger.changed = true;
        });

        const title = form.querySelector(base.selectors.details.title);
        title.addEventListener('change', validateTriggerDetailsForm);
        title.addEventListener('keyup', validateTriggerDetailsForm);

        $(`${formSelector} ${base.selectors.details.alertDefinition.rule.typeSelect}`).each((index, target) => {
            const type = target.value;
            const alertDefinitionItem = target.closest(base.selectors.details.alertDefinition.item);
            const alertDefinitionRank = parseInt(alertDefinitionItem.dataset.rank);
            const ruleItem = target.closest(base.selectors.details.alertDefinition.rule.item);
            const ruleItemRank = parseInt(ruleItem.dataset.rank);

            const rule = details.currentAlertTrigger.findDefinition(alertDefinitionRank).findRule(ruleItemRank);
            const newRuleItem = base.elements.details.alertDefinition.rule.item(alertDefinitionItem, ruleItemRank);
            if (type !== 'none') {
                view.initDateTimeField(alertDefinitionItem, newRuleItem);
                const timeSlotInput = newRuleItem.querySelector(base.selectors.details.alertDefinition.rule.timeSlotInput);
                timeSlotInput.bulmaCalendar.on('select clear', () => {
                    validateAlertTriggerRuleForm(rule, newRuleItem);
                });
                newRuleItem.querySelector(_base.selectors.dateTimeFooterCancelButton).addEventListener('click', validateAlertTriggerRuleForm(rule, newRuleItem));
            }
        });

        form.querySelector(base.selectors.details.alertDefinition.section).addEventListener('click', (e) => {
            const target = e.target;

            if (target.matches(`${base.selectors.details.alertDefinition.addButton}, ${base.selectors.details.alertDefinition.addButtonChildren}`)) {
                addDefaultAlertTriggerDefinition();
            }

            if (target.matches(`${base.selectors.details.alertDefinition.deleteButton}, ${base.selectors.details.alertDefinition.deleteButtonChildren}`)) {
                _base.swalWithBulmaButtons.fire({
                    title: 'Alert definition removal confirmation',
                    text: 'This alert definition and all its rules will be removed. Are you sure?',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: '<span class="icon"><i class="fas fa-trash-alt"></i></span><span>Remove it!</span>',
                    cancelButtonText: '<span class="icon"><i class="fas fa-undo"></i></span><span>Undo</span>'
                }).then(result => {
                    if (result.value) {
                        const item = target.closest(base.selectors.details.alertDefinition.item);
                        const rank = parseInt(item.dataset.rank);
                        view.details.deleteDefinition(form, item);
                        details.currentAlertTrigger.removeDefinition(rank);
                        view.details.updateDefinitionsCount(form, details.currentAlertTrigger.definitions.length);
                    }
                });
            }

            if (target.matches(`${base.selectors.details.alertDefinition.rule.addButton}, ${base.selectors.details.alertDefinition.rule.addButtonChildren}`)) {
                const item = target.closest(base.selectors.details.alertDefinition.item);
                const rank = parseInt(item.dataset.rank);
                let alertDefinition = details.currentAlertTrigger.findDefinition(rank);
                addDefaultAlertTriggerRule(processSelection.watchedProcessSelect(form).value, alertDefinition, item);
            }

            if (target.matches(`${base.selectors.details.alertDefinition.rule.deleteButton}, ${base.selectors.details.alertDefinition.rule.deleteButtonChildren}`)) {
                _base.swalWithBulmaButtons.fire({
                    title: 'Rule removal confirmation',
                    text: 'This rule will be removed. Are you sure?',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: '<span class="icon"><i class="fas fa-trash-alt"></i></span><span>Remove it!</span>',
                    cancelButtonText: '<span class="icon"><i class="fas fa-undo"></i></span><span>Undo</span>'
                }).then(result => {
                    if (result.value) {
                        const alertDefinitionItem = target.closest(base.selectors.details.alertDefinition.item);
                        const alertDefinitionRank = parseInt(alertDefinitionItem.dataset.rank);
                        const ruleItem = target.closest(base.selectors.details.alertDefinition.rule.item);
                        const ruleItemRank = parseInt(ruleItem.dataset.rank);
                        let alertDefinition = details.currentAlertTrigger.findDefinition(alertDefinitionRank);
                        alertDefinition.removeRule(ruleItemRank);
                        view.details.deleteRule(alertDefinitionItem, ruleItem);
                        view.details.updateDefinitionValidity(alertDefinitionItem, alertDefinition.isValid());
                    }
                });
            }
        });

        form.querySelector(base.selectors.details.alertDefinition.section).addEventListener('change', (e) => {
            const target = e.target;

            if (target.matches(base.selectors.details.alertDefinition.levelSelect)) {
                const level = target.value;
                const item = target.closest(base.selectors.details.alertDefinition.item);
                const rank = parseInt(item.dataset.rank);
                view.details.updateDefinitionLevel(item, level);
                let alertDefinition = details.currentAlertTrigger.findDefinition(rank);
                alertDefinition.level = level;
            }

            if (target.matches(base.selectors.details.alertDefinition.rule.typeSelect)) {
                const type = target.value;
                const alertDefinitionItem = target.closest(base.selectors.details.alertDefinition.item);
                const alertDefinitionRank = parseInt(alertDefinitionItem.dataset.rank);
                const ruleItem = target.closest(base.selectors.details.alertDefinition.rule.item);
                const ruleItemRank = parseInt(ruleItem.dataset.rank);
                const rule = details.currentAlertTrigger.findDefinition(alertDefinitionRank).findRule(ruleItemRank);
                
                updateAlertTriggerRuleType(processSelection.watchedProcessSelect(form).value, rule, ruleItem, type).then(response => {
                    const newRuleItem = base.elements.details.alertDefinition.rule.item(alertDefinitionItem, ruleItemRank);
                    if (type !== 'none') {
                        const timeSlotInput = newRuleItem.querySelector(base.selectors.details.alertDefinition.rule.timeSlotInput);
                        timeSlotInput.bulmaCalendar.on('select clear', () => {
                            validateAlertTriggerRuleForm(rule, newRuleItem);
                        });
                        newRuleItem.querySelector(_base.selectors.dateTimeFooterCancelButton).addEventListener('click', validateAlertTriggerRuleForm(rule, newRuleItem));
                    }
                });
            }

            if (target.matches(base.selectors.details.alertDefinition.rule.parameter)) {
                const alertDefinitionItem = target.closest(base.selectors.details.alertDefinition.item);
                const alertDefinitionRank = parseInt(alertDefinitionItem.dataset.rank);
                const ruleItem = target.closest(base.selectors.details.alertDefinition.rule.item);
                const ruleItemRank = parseInt(ruleItem.dataset.rank);
                const alertDefinition = details.currentAlertTrigger.findDefinition(alertDefinitionRank);
                const rule = alertDefinition.findRule(ruleItemRank);

                validateAlertTriggerRuleForm(rule, ruleItem);
            }
        });

        form.querySelector(base.selectors.details.alertDefinition.section).addEventListener('keyup', (e) => {
            const target = e.target;

            if (target.matches(base.selectors.details.alertDefinition.descriptionInput)) {
                const description = target.value;
                const item = target.closest(base.selectors.details.alertDefinition.item);
                const rank = parseInt(item.dataset.rank);
                view.details.updateDefinitionDescription(item, description);
                let alertDefinition = details.currentAlertTrigger.findDefinition(rank);
                alertDefinition.description = description;
                view.details.updateDefinitionValidity(item, details.currentAlertTrigger.findDefinition(rank).isValid());
            }

            if (target.matches(base.selectors.details.alertDefinition.rule.parameter)) {
                const alertDefinitionItem = target.closest(base.selectors.details.alertDefinition.item);
                const alertDefinitionRank = parseInt(alertDefinitionItem.dataset.rank);
                const ruleItem = target.closest(base.selectors.details.alertDefinition.rule.item);
                const ruleItemRank = parseInt(ruleItem.dataset.rank);
                const alertDefinition = details.currentAlertTrigger.findDefinition(alertDefinitionRank);
                const rule = alertDefinition.findRule(ruleItemRank);

                validateAlertTriggerRuleForm(rule, ruleItem);
            }
        });
    } catch (error) {
        console.log(`Unable to init trigger details step: ${error}`);
        throw(error);
    }
};

const initAlertTriggerConfirmation = () => {
    try {
        const form = (currentMode === 'add' ? base.elements.addForm : document.querySelector(base.selectors.editForm));
        const activeStepContent = form.querySelector(base.selectors.activeStepContent);

        const activateButton = form.querySelector(base.selectors.confirmation.activateButton);
        activateButton.disabled = (details.currentAlertTrigger && details.currentAlertTrigger.active);

        activeStepContent.querySelector(base.selectors.confirmation.notification).addEventListener('click', (e) => {
            const target = e.target;

            if (target.matches(`${base.selectors.confirmation.activateButton}, ${base.selectors.confirmation.activateButtonChildren}`)) {
                _base.renderLoader(activateButton);
                try {
                    details.currentAlertTrigger.activate().then(response => {
                        if (currentMode === 'edit') {
                            _base.renderLoader(base.elements.formsSection);
                            details.currentAlertTrigger.loadEditFormButtons().then(response => {
                                form.querySelector(base.selectors.editFormButtonsSection).innerHTML = details.currentAlertTrigger.editFormButtons;
                                _base.clearLoader(base.elements.formsSection);
                            });
                        }
                        updateTable().then(response =>  {
                            _base.clearLoader(activateButton);
                            activateButton.disabled = true;
                        });
                    });
                } catch (error) {
                    _base.clearLoader(activateButton);
                    console.log(error);
                }
            }

            if (target.matches(`${base.selectors.confirmation.closeButton}, ${base.selectors.confirmation.closeButtonChildren}`)) {
                details.currentAlertTrigger = new AlertTrigger();
                resetForm();
                validateProcessSelectionForm();
                loadAddForm(e);
            }
        });
    } catch (error) {
        console.log(`Unable to init trigger confirmation step: ${error}`)
    }
};

const validateProcessSelectionForm = () => {
    const form = (currentMode === 'add' ? base.elements.addForm : document.querySelector(base.selectors.editForm));

    let errors = [];

    const clientSelectValid = !(processSelection.clientSelect(form).value === "0");
    _base.toggleSuccessDangerState(processSelection.clientSelect(form).parentNode, clientSelectValid);
    if (!clientSelectValid) {
        errors.push('You need to select a customer');
    }
    
    const watchedProcessSelectValid = !(processSelection.watchedProcessSelect(form).value === "0");
    if (!processSelection.watchedProcessSelect.disabled) {
        _base.toggleSuccessDangerState(processSelection.watchedProcessSelect(form).parentNode, watchedProcessSelectValid);
        if (!watchedProcessSelectValid) {
            errors.push('You need to select a watched process');
        }
    } else {
        _base.removeStates(processSelection.watchedProcessSelect(form).parentNode);
    }

    return errors;
};

const validateTriggerDetailsForm = () => {
    const form = (currentMode === 'add' ? base.elements.addForm : document.querySelector(base.selectors.editForm));
    const formSelector = (currentMode === 'add' ? base.selectors.addForm : base.selectors.editForm);

    let errors = [];

    const title = form.querySelector(base.selectors.details.title);
    const titleValid = !(title.value.trim() === '');
    _base.toggleSuccessDangerState(title, titleValid);
    if (!titleValid) {
        errors.push('You need to enter a title');
    } else {
        details.currentAlertTrigger.title = title.value.trim();
    }

    if (details.currentAlertTrigger.definitions.length === 0) {
        errors.push('You need to add at least 1 alert definition');
    }

    const alertDefinitionsValid = details.currentAlertTrigger.isValid();
    if (!alertDefinitionsValid) {
        errors.push('You need to fix errors on all alert definitions');
    }

    return errors;
};

const loadProcesses = async (e) => {
    const form = base.elements.addForm;
    const activeStepContent = form.querySelector(base.selectors.activeStepContent);
    try {
        _base.renderLoader(activeStepContent);
        _base.removeSelectOptions(processSelection.watchedProcessSelect(form), true);
        const id = e.target.value.trim();
        if (id !== "0") {
            const client = new Client(id);
            return new Promise((resolve, reject) => {
                resolve(
                    client.get().then(response => {
                        const processes = client.data.watched_automated_processes;
                        view.processSelection.updateProcesses(processes);
                        _base.clearLoader(activeStepContent);
                    })
                );
            });
        } else {
            processSelection.watchedProcessSelect(form).disabled = true;
            _base.clearLoader(activeStepContent);
        }
    } catch (error) {
        _base.clearLoader(activeStepContent);
        console.log(error);
    }
};

const loadDefaultAlertTriggerDetails = async (watchedAutomatedProcessId) => {
    const form = (currentMode === 'add' ? base.elements.addForm : document.querySelector(base.selectors.editForm));
    const activeStepContent = form.querySelector(base.selectors.activeStepContent);

    try {
        _base.renderLoader(activeStepContent);

        if (currentMode === 'edit' && !('data' in details.currentAlertTrigger)) {
            details.currentAlertTrigger = new AlertTrigger(form.dataset.id);
            details.currentAlertTrigger.get().then(response => {
                initAlertTriggerDetails();
            });
        } else {
            if (
                (!(details.currentWatchedAutomatedProcess
                && details.currentWatchedAutomatedProcess.id === processSelection.watchedProcessSelect(form).value))
                && !('data' in details.currentAlertTrigger)
            ) {
                if (currentMode === 'add') {
                    configuration.getAlertTriggersDefaultDetails(watchedAutomatedProcessId).then(async (response) => {
                        activeStepContent.innerHTML = response.data;
                        initAlertTriggerDetails();
                    });
                }
            }
        }

        details.currentWatchedAutomatedProcess = new WatchedAutomatedProcess(processSelection.watchedProcessSelect(form).value);
        await details.currentWatchedAutomatedProcess.get();
        details.currentClient = new Client(details.currentWatchedAutomatedProcess.data.client_id);
        await details.currentClient.get();

        _base.clearLoader(activeStepContent);
    } catch (error) {
        _base.clearLoader(activeStepContent);
        console.log(error);
    }
};

const loadDefaultAlertTriggerSummary = async (watchedAutomatedProcessId) => {
    const form = (currentMode === 'add' ? base.elements.addForm : document.querySelector(base.selectors.editForm));
    const activeStepContent = form.querySelector(base.selectors.activeStepContent);
    activeStepContent.innerHTML = '';

    try {
        _base.renderLoader(activeStepContent);
        return new Promise((resolve, reject) => {
            resolve(
                configuration.getAlertTriggersDefaultSummary(
                    watchedAutomatedProcessId,
                    form.querySelector(base.selectors.details.title).value,
                    details.currentAlertTrigger.definitions
                ).then(async (response) => {
                    activeStepContent.innerHTML = response.data.view;
                    details.currentAlertTrigger.loadDefinitions(response.data.alertTrigger);

                    _base.clearLoader(activeStepContent);
                })
            );
        });
    } catch (error) {
        _base.clearLoader(activeStepContent);
        console.log(error);
    }
};

const addDefaultAlertTriggerDefinition = async () => {
    const form = (currentMode === 'add' ? base.elements.addForm : document.querySelector(base.selectors.editForm));
    const activeStepContent = form.querySelector(base.selectors.activeStepContent);
    try {
        _base.renderLoader(activeStepContent);
        return new Promise((resolve, reject) => {
            const rank = details.currentAlertTrigger.definitions.length + 1;
            resolve(
                configuration.getAlertTriggersDefaultDefinition(rank).then(response => {
                    view.details.addDefinition(form, response.data);
                    view.details.updateDefinitionsCount(form, rank);

                    const alertDefinition = new AlertTriggerDefinition(null, rank);
                    details.currentAlertTrigger.addDefinition(alertDefinition);

                    _base.clearLoader(activeStepContent);
                })
            );
        });
    } catch (error) {
        _base.clearLoader(activeStepContent);
        console.log(error);
    }
};

const addDefaultAlertTriggerRule = async (watchedAutomatedProcessId, alertDefinition, alertDefinitionItem) => {
    const form = (currentMode === 'add' ? base.elements.addForm : document.querySelector(base.selectors.editForm));
    const activeStepContent = form.querySelector(base.selectors.activeStepContent);
    try {
        _base.renderLoader(activeStepContent);
        return new Promise((resolve, reject) => {
            const rank = alertDefinition.rules.length + 1;
            resolve(
                configuration.getAlertTriggersDefaultRule(watchedAutomatedProcessId, rank).then(response => {
                    view.details.addRule(alertDefinitionItem, response.data);
                    
                    const alertRule = new AlertTriggerRule(null, alertDefinition.rank, rank);
                    alertDefinition.addRule(alertRule);
                    
                    view.details.updateDefinitionValidity(alertDefinitionItem, alertDefinition.isValid());

                    _base.clearLoader(activeStepContent);
                })
            );
        });
    } catch (error) {
        _base.clearLoader(activeStepContent);
        console.log(error);
    }
};

const updateAlertTriggerRuleType = async (watchedAutomatedProcessId, rule, ruleItem, type) => {
    const form = (currentMode === 'add' ? base.elements.addForm : document.querySelector(base.selectors.editForm));
    const activeStepContent = form.querySelector(base.selectors.activeStepContent);
    try {
        _base.renderLoader(activeStepContent);
        return new Promise((resolve, reject) => {
            const rank = rule.rank;
            resolve(
                configuration.getAlertTriggersDefaultRule(watchedAutomatedProcessId, rank, type).then(response => {
                    view.details.updateRule(ruleItem, response.data, type);
                    rule.type = type;
                    _base.clearLoader(activeStepContent);
                })
            );
        });
    } catch (error) {
        _base.clearLoader(activeStepContent);
        console.log(error);
    }
};

const validateAlertTriggerRuleForm = (rule, ruleItem) => {
    try {
        let valid = false;
        const alertDefinitionItem = base.elements.details.alertDefinition.item(rule.definitionRank);
        const rank = ruleItem.dataset.rank;
        const type = ruleItem.querySelector(base.selectors.details.alertDefinition.rule.typeSelect).value;
        const title = ruleItem.querySelector(base.selectors.details.alertDefinition.rule.title);
        const titleIcon = ruleItem.querySelector(base.selectors.details.alertDefinition.rule.titleIcon);

        if (type === 'jobs-min-duration') {
            valid = validateAlertTriggerJobsMinDurationRule(rule, ruleItem);
        } else if (type === 'jobs-max-duration') {
            valid = validateAlertTriggerJobsMaxDurationRule(rule, ruleItem);
        } else if (type === 'faulted-jobs-percentage') {
            valid = validateAlertTriggerFaultedJobsPercentageRule(rule, ruleItem);
        } else if (type === 'failed-queue-items-percentage') {
            valid = validateAlertTriggerFailedQueueItemsPercentageRule(rule, ruleItem);
        } else if (type === 'elastic-search-query') {
            valid = validateAlertTriggerElasticSearchQueryRule(rule, ruleItem);
        } else if (type === 'elastic-search-multiple-queries-comparison') {
            valid = validateAlertTriggerElasticSearchMultipleQueriesComparisonRule(rule, ruleItem);
        }

        rule.valid = valid;
        view.details.updateDefinitionValidity(alertDefinitionItem, details.currentAlertTrigger.findDefinition(rule.definitionRank).isValid());

        _base.toggleSuccessDangerState(title, valid, true);
        _base.toggleSuccessDangerState(titleIcon, valid, true);
    } catch (error) {
        console.log(error);
    }
};

const validateAlertTriggerJobsMinDurationRule = (rule, ruleItem) => {
    let valid = false;
    let parameters = {
        specific: {},
        standard: {
            timeSlot: {},
            triggeringDays: {},
            involvedEntities: {}
        }
    };

    try {
        const minimalDurationInput = ruleItem.querySelector(base.selectors.details.alertDefinition.rule.jobsDurationControls.minimalDurationInput);

        const minimalDurationInputValid = minimalDurationInput.value.trim() !== '' && _base.isNormalInteger(minimalDurationInput.value)
            && parseInt(minimalDurationInput.value) > 0;
        _base.toggleSuccessDangerState(minimalDurationInput, minimalDurationInputValid);
        
        if (minimalDurationInputValid) {
            parameters.specific = {
                minimalDuration: parseInt(minimalDurationInput.value)
            };
        }
        
        const timeSlotInputValid = validateAlertTriggerRuleTimeSlotControls(ruleItem, parameters);
        const relativeTimeSlotInputValid = validateAlertTriggerRuleRelativeTimeSlotControls(ruleItem, parameters);
        const triggeringDaysSelectionValid = validateAlertTriggerRuleTriggeringDaysControls(ruleItem, parameters);
        const involvedProcessesSelectionValid = validateAlertTriggerRuleInvolvedProcessesSelectionControls(ruleItem, parameters);
        const involvedRobotsSelectionValid = validateAlertTriggerRuleInvolvedRobotsSelectionControls(ruleItem, parameters);

        rule.parameters = parameters;

        valid = minimalDurationInputValid && timeSlotInputValid && triggeringDaysSelectionValid
            && involvedProcessesSelectionValid && involvedRobotsSelectionValid;
    } catch (error) {
        console.log(error);
    }

    return valid;
};

const validateAlertTriggerJobsMaxDurationRule = (rule, ruleItem) => {
    let valid = false;
    let parameters = {
        specific: {},
        standard: {
            timeSlot: {},
            triggeringDays: {},
            involvedEntities: {}
        }
    };

    try {
        const maximalDurationInput = ruleItem.querySelector(base.selectors.details.alertDefinition.rule.jobsDurationControls.maximalDurationInput);

        const maximalDurationInputValid = maximalDurationInput.value.trim() !== '' && _base.isNormalInteger(maximalDurationInput.value)
            && parseInt(maximalDurationInput.value) > 0;
        _base.toggleSuccessDangerState(maximalDurationInput, maximalDurationInputValid);
        
        if (maximalDurationInputValid) {
            parameters.specific = {
                maximalDuration: parseInt(maximalDurationInput.value)
            };
        }
        
        const timeSlotInputValid = validateAlertTriggerRuleTimeSlotControls(ruleItem, parameters);
        const triggeringDaysSelectionValid = validateAlertTriggerRuleTriggeringDaysControls(ruleItem, parameters);
        const involvedProcessesSelectionValid = validateAlertTriggerRuleInvolvedProcessesSelectionControls(ruleItem, parameters);
        const involvedRobotsSelectionValid = validateAlertTriggerRuleInvolvedRobotsSelectionControls(ruleItem, parameters);

        rule.parameters = parameters;

        valid = maximalDurationInputValid && timeSlotInputValid && triggeringDaysSelectionValid
            && involvedProcessesSelectionValid && involvedRobotsSelectionValid;
    } catch (error) {
        console.log(error);
    }

    return valid;
};

const validateAlertTriggerFaultedJobsPercentageRule = (rule, ruleItem) => {
    let valid = false;
    let parameters = {
        specific: {},
        standard: {
            timeSlot: {},
            triggeringDays: {},
            involvedEntities: {}
        }
    };

    try {
        const maximalPercentageInput = ruleItem.querySelector(base.selectors.details.alertDefinition.rule.faultedJobsPercentageControls.maximalPercentageInput);

        const maximalPercentageInputValid = maximalPercentageInput.value.trim() !== '' && _base.isNormalInteger(maximalPercentageInput.value);
        _base.toggleSuccessDangerState(maximalPercentageInput, maximalPercentageInputValid);
        
        if (maximalPercentageInputValid) {
            parameters.specific = {
                maximalPercentage: parseInt(maximalPercentageInput.value)
            };
        }
        
        const timeSlotInputValid = validateAlertTriggerRuleTimeSlotControls(ruleItem, parameters);
        const relativeTimeSlotInputValid = validateAlertTriggerRuleRelativeTimeSlotControls(ruleItem, parameters);
        const triggeringDaysSelectionValid = validateAlertTriggerRuleTriggeringDaysControls(ruleItem, parameters);
        const involvedProcessesSelectionValid = validateAlertTriggerRuleInvolvedProcessesSelectionControls(ruleItem, parameters);
        const involvedRobotsSelectionValid = validateAlertTriggerRuleInvolvedRobotsSelectionControls(ruleItem, parameters);

        rule.parameters = parameters;

        valid = maximalPercentageInputValid && timeSlotInputValid && relativeTimeSlotInputValid
            && triggeringDaysSelectionValid && involvedProcessesSelectionValid && involvedRobotsSelectionValid;
    } catch (error) {
        console.log(error);
    }

    return valid;
};

const validateAlertTriggerFailedQueueItemsPercentageRule = (rule, ruleItem) => {
    let valid = false;
    let parameters = {
        specific: {},
        standard: {
            timeSlot: {},
            triggeringDays: {},
            involvedEntities: {}
        }
    };

    try {
        const maximalPercentageInput = ruleItem.querySelector(base.selectors.details.alertDefinition.rule.failedQueueItemsPercentageControls.maximalPercentageInput);

        const maximalPercentageInputValid = maximalPercentageInput.value.trim() !== '' && _base.isNormalInteger(maximalPercentageInput.value);
        _base.toggleSuccessDangerState(maximalPercentageInput, maximalPercentageInputValid);
        
        if (maximalPercentageInputValid) {
            parameters.specific = {
                maximalPercentage: parseInt(maximalPercentageInput.value)
            };
        }
        
        const timeSlotInputValid = validateAlertTriggerRuleTimeSlotControls(ruleItem, parameters);
        const relativeTimeSlotInputValid = validateAlertTriggerRuleRelativeTimeSlotControls(ruleItem, parameters);
        const triggeringDaysSelectionValid = validateAlertTriggerRuleTriggeringDaysControls(ruleItem, parameters);
        const involvedQueuesSelectionValid = validateAlertTriggerRuleInvolvedQueuesSelectionControls(ruleItem, parameters);

        rule.parameters = parameters;

        valid = maximalPercentageInputValid && timeSlotInputValid && relativeTimeSlotInputValid
            && triggeringDaysSelectionValid && involvedQueuesSelectionValid;
    } catch (error) {
        console.log(error);
    }

    return valid;
};

const validateAlertTriggerElasticSearchQueryRule = (rule, ruleItem) => {
    let valid = false;
    let parameters = {
        specific: {},
        standard: {
            timeSlot: {},
            triggeringDays: {},
            involvedEntities: {}
        }
    };

    try {
        const searchQueryInput = ruleItem.querySelector(base.selectors.details.alertDefinition.rule.elasticSearchQueryControls.searchQueryInput);
        const lowerCountInput = ruleItem.querySelector(base.selectors.details.alertDefinition.rule.elasticSearchQueryControls.lowerCountInput);
        const higherCountInput = ruleItem.querySelector(base.selectors.details.alertDefinition.rule.elasticSearchQueryControls.higherCountInput);

        const searchQueryInputValid = searchQueryInput.value.trim() !== '' && _base.isValidLuceneString(`'${searchQueryInput.value.trim()}'`);
        _base.toggleSuccessDangerState(searchQueryInput, searchQueryInputValid);

        const lowerCountInputValid = lowerCountInput.value.trim() !== '' && _base.isNormalInteger(lowerCountInput.value) || (
            higherCountInput.value.trim() !== '' && _base.isNormalInteger(higherCountInput.value) && (parseInt(higherCountInput.value) > 0)
        );
        _base.toggleSuccessDangerState(lowerCountInput, lowerCountInputValid);

        const higherCountInputValid = higherCountInput.value.trim() === '' ||
            (higherCountInput.value.trim() !== '' && _base.isNormalInteger(higherCountInput.value)
            && lowerCountInputValid && parseInt(higherCountInput.value) > (parseInt(lowerCountInput.value) + 1)) ||
            (higherCountInput.value.trim() !== '' && _base.isNormalInteger(higherCountInput.value));
        _base.toggleSuccessDangerState(higherCountInput, higherCountInputValid);
        
        if (lowerCountInputValid && higherCountInputValid) {
            parameters.specific = {
                searchQuery: searchQueryInput.value.trim(),
                lowerCount: parseInt(lowerCountInput.value),
                higherCount: higherCountInput.value.trim() !== '' ? parseInt(higherCountInput.value) : ''
            };
        }
        
        const timeSlotInputValid = validateAlertTriggerRuleTimeSlotControls(ruleItem, parameters);
        const relativeTimeSlotInputValid = validateAlertTriggerRuleRelativeTimeSlotControls(ruleItem, parameters);
        const triggeringDaysSelectionValid = validateAlertTriggerRuleTriggeringDaysControls(ruleItem, parameters);
        const involvedProcessesSelectionValid = validateAlertTriggerRuleInvolvedProcessesSelectionControls(ruleItem, parameters);
        const involvedRobotsSelectionValid = validateAlertTriggerRuleInvolvedRobotsSelectionControls(ruleItem, parameters);

        rule.parameters = parameters;

        valid = searchQueryInputValid && lowerCountInputValid && higherCountInputValid && timeSlotInputValid
            && relativeTimeSlotInputValid && triggeringDaysSelectionValid && involvedProcessesSelectionValid && involvedRobotsSelectionValid;
    } catch (error) {
        console.log(error);
    }

    return valid;
};

const validateAlertTriggerElasticSearchMultipleQueriesComparisonRule = (rule, ruleItem) => {
    let valid = false;
    let parameters = {
        specific: {},
        standard: {
            timeSlot: {},
            triggeringDays: {},
            involvedEntities: {}
        }
    };

    try {
        const leftSearchQueryInput = ruleItem.querySelector(base.selectors.details.alertDefinition.rule.elasticSearchMultipleQueriesControls.leftSearchQueryInput);
        const rightSearchQueryInput = ruleItem.querySelector(base.selectors.details.alertDefinition.rule.elasticSearchMultipleQueriesControls.rightSearchQueryInput);
        const comparisonOperatorSelect = ruleItem.querySelector(base.selectors.details.alertDefinition.rule.elasticSearchMultipleQueriesControls.comparisonOperatorSelect);

        const leftSearchQueryInputValid = leftSearchQueryInput.value.trim() !== '' && _base.isValidLuceneString(`'${leftSearchQueryInput.value.trim()}'`);
        _base.toggleSuccessDangerState(leftSearchQueryInput, leftSearchQueryInputValid);

        const rightSearchQueryInputValid = rightSearchQueryInput.value.trim() !== '' && _base.isValidLuceneString(`'${rightSearchQueryInput.value.trim()}'`);
        _base.toggleSuccessDangerState(rightSearchQueryInput, rightSearchQueryInputValid);
        
        const comparisonOperatorSelectValid = comparisonOperatorSelect.value !== 'none';
        _base.toggleSuccessDangerState(comparisonOperatorSelect.parentNode, comparisonOperatorSelectValid);
        
        if (leftSearchQueryInputValid && rightSearchQueryInputValid && comparisonOperatorSelectValid) {
            parameters.specific = {
                leftSearchQuery: leftSearchQueryInput.value.trim(),
                rightSearchQuery: rightSearchQueryInput.value.trim(),
                comparisonOperator: comparisonOperatorSelect.value
            };
        }
        
        const timeSlotInputValid = validateAlertTriggerRuleTimeSlotControls(ruleItem, parameters);
        const relativeTimeSlotInputValid = validateAlertTriggerRuleRelativeTimeSlotControls(ruleItem, parameters);
        const triggeringDaysSelectionValid = validateAlertTriggerRuleTriggeringDaysControls(ruleItem, parameters);
        const involvedProcessesSelectionValid = validateAlertTriggerRuleInvolvedProcessesSelectionControls(ruleItem, parameters);
        const involvedRobotsSelectionValid = validateAlertTriggerRuleInvolvedRobotsSelectionControls(ruleItem, parameters);

        rule.parameters = parameters;

        valid = leftSearchQueryInputValid && rightSearchQueryInputValid && comparisonOperatorSelectValid && timeSlotInputValid
            && relativeTimeSlotInputValid && triggeringDaysSelectionValid && involvedProcessesSelectionValid && involvedRobotsSelectionValid;
    } catch (error) {
        console.log(error);
    }

    return valid;
};

const validateAlertTriggerRuleTimeSlotControls = (ruleItem, parameters) => {
    let valid = false;

    try {
        const timeSlotInput = ruleItem.querySelector(base.selectors.details.alertDefinition.rule.timeSlotInput);

        const minStart = timeSlotInput.dataset.startTime;
        const maxEnd = timeSlotInput.dataset.endTime;

        const timeSlotInputFrom = timeSlotInput.parentNode.querySelector(_base.selectors.dateTimeCalendarFromInput);
        const timeSlotInputTo = timeSlotInput.parentNode.querySelector(_base.selectors.dateTimeCalendarToInput);

        valid = timeSlotInputFrom.value.trim() !== '' && timeSlotInputTo.value.trim() !== ''
            && _base.timeStringToFloat(timeSlotInputFrom.value.trim()) >= _base.timeStringToFloat(minStart)
            && _base.timeStringToFloat(timeSlotInputTo.value.trim()) <= _base.timeStringToFloat(maxEnd)
            && _base.timeStringToFloat(timeSlotInputFrom.value.trim()) < _base.timeStringToFloat(timeSlotInputTo.value.trim());
        
        if (valid) {
            const calendar = timeSlotInput.bulmaCalendar;
            parameters.standard.timeSlot = {
                from: calendar.startTime.toTimeString().split(' ')[0],
                to: calendar.endTime.toTimeString().split(' ')[0]
            };
        }
            
        const timeSlotInputWrapper = timeSlotInput.closest(_base.selectors.dateTimeCalendarWrapper);
        _base.toggleSuccessDangerState(timeSlotInputWrapper, valid);
    } catch (error) {
        console.log(error);
    }

    return valid;
};

const validateAlertTriggerRuleRelativeTimeSlotControls = (ruleItem, parameters) => {
    let valid = false;

    try {
        const relativeTimeSlotInput = ruleItem.querySelector(base.selectors.details.alertDefinition.rule.relativeTimeSlotInput);

        valid = (relativeTimeSlotInput.value.trim() !== '' && _base.isNormalInteger(relativeTimeSlotInput.value)) ||
            relativeTimeSlotInput.value.trim() === '';
        
        if (valid) {
            parameters.standard.relativeTimeSlot = relativeTimeSlotInput.value.trim() !== '' ? parseInt(relativeTimeSlotInput.value) : null;
        }
        
        _base.toggleSuccessDangerState(relativeTimeSlotInput, valid);
    } catch (error) {
        console.log(error);
    }

    return valid;
};

const validateAlertTriggerRuleTriggeringDaysControls = (ruleItem, parameters) => {
    let valid = false;

    try {
        const triggeringDaysTitle = ruleItem.querySelector(base.selectors.details.alertDefinition.rule.triggeringDays.title);
        const triggeringDaysTitleIcon = ruleItem.querySelector(base.selectors.details.alertDefinition.rule.triggeringDays.titleIcon);
        const triggeringDaysMondayCheckbox = ruleItem.querySelector(base.selectors.details.alertDefinition.rule.triggeringDays.mondayCheckbox);
        const triggeringDaysTuesdayCheckbox = ruleItem.querySelector(base.selectors.details.alertDefinition.rule.triggeringDays.tuesdayCheckbox);
        const triggeringDaysWednesdayCheckbox = ruleItem.querySelector(base.selectors.details.alertDefinition.rule.triggeringDays.wednesdayCheckbox);
        const triggeringDaysThursdayCheckbox = ruleItem.querySelector(base.selectors.details.alertDefinition.rule.triggeringDays.thursdayCheckbox);
        const triggeringDaysFridayCheckbox = ruleItem.querySelector(base.selectors.details.alertDefinition.rule.triggeringDays.fridayCheckbox);
        const triggeringDaysSaturdayCheckbox = ruleItem.querySelector(base.selectors.details.alertDefinition.rule.triggeringDays.saturdayCheckbox);
        const triggeringDaysSundayCheckbox = ruleItem.querySelector(base.selectors.details.alertDefinition.rule.triggeringDays.sundayCheckbox);

        valid = triggeringDaysMondayCheckbox.checked || triggeringDaysTuesdayCheckbox.checked ||
        triggeringDaysWednesdayCheckbox.checked || triggeringDaysThursdayCheckbox.checked ||
        triggeringDaysFridayCheckbox.checked || triggeringDaysSaturdayCheckbox.checked ||
        triggeringDaysSundayCheckbox.checked;
        
        if (valid) {
            parameters.standard.triggeringDays = {
                monday: triggeringDaysMondayCheckbox.checked,
                tuesday: triggeringDaysTuesdayCheckbox.checked,
                wednesday: triggeringDaysWednesdayCheckbox.checked,
                thursday: triggeringDaysThursdayCheckbox.checked,
                friday: triggeringDaysFridayCheckbox.checked,
                saturday: triggeringDaysSaturdayCheckbox.checked,
                sunday: triggeringDaysSundayCheckbox.checked
            };
        }

        _base.toggleSuccessDangerState(triggeringDaysTitle, valid, true);
        _base.toggleSuccessDangerState(triggeringDaysTitleIcon, valid, true);
    } catch (error) {
        console.log(error);
    }

    return valid;
};

const validateAlertTriggerRuleInvolvedProcessesSelectionControls = (ruleItem, parameters) => {
    let valid = false;

    try {
        const involvedProcessesTitle = ruleItem.querySelector(base.selectors.details.alertDefinition.rule.involvedEntitiesControls.processes.title);
        const involvedProcessesTitleIcon = ruleItem.querySelector(base.selectors.details.alertDefinition.rule.involvedEntitiesControls.processes.titleIcon);
        const involvedProcessesCount = ruleItem.querySelector(base.selectors.details.alertDefinition.rule.involvedEntitiesControls.processes.count);
        
        const selectedProcesses = 
            [...ruleItem.querySelectorAll(base.selectors.details.alertDefinition.rule.involvedEntitiesControls.processes.switch)]
            .filter(item => { return item.checked });
        
        const selectedProcessesCount = selectedProcesses.length;
        involvedProcessesCount.innerHTML = selectedProcessesCount;

        valid = selectedProcessesCount > 0;

        if (valid) {
            parameters.standard.involvedEntities.processes = selectedProcesses.map(item => { return item.dataset.id });
        }

        _base.toggleSuccessDangerState(involvedProcessesTitle, valid, true);
        _base.toggleSuccessDangerState(involvedProcessesTitleIcon, valid, true);
        _base.toggleSuccessDangerState(involvedProcessesCount, valid);
    } catch (error) {
        console.log(error);
    }

    return valid;
};

const validateAlertTriggerRuleInvolvedRobotsSelectionControls = (ruleItem, parameters) => {
    let valid = false;

    try {
        const involvedRobotsTitle = ruleItem.querySelector(base.selectors.details.alertDefinition.rule.involvedEntitiesControls.robots.title);
        const involvedRobotsTitleIcon = ruleItem.querySelector(base.selectors.details.alertDefinition.rule.involvedEntitiesControls.robots.titleIcon);
        const involvedRobotsCount = ruleItem.querySelector(base.selectors.details.alertDefinition.rule.involvedEntitiesControls.robots.count);

        const selectedRobots = 
            [...ruleItem.querySelectorAll(base.selectors.details.alertDefinition.rule.involvedEntitiesControls.robots.switch)]
            .filter(item => { return item.checked });

        const selectedRobotsCount = selectedRobots.length;
        involvedRobotsCount.innerHTML = selectedRobotsCount;

        valid = selectedRobotsCount > 0;

        if (valid) {
            parameters.standard.involvedEntities.robots = selectedRobots.map(item => { return item.dataset.id });
        }

        _base.toggleSuccessDangerState(involvedRobotsTitle, valid, true);
        _base.toggleSuccessDangerState(involvedRobotsTitleIcon, valid, true);
        _base.toggleSuccessDangerState(involvedRobotsCount, valid);
    } catch (error) {
        console.log(error);
    }

    return valid;
};

const validateAlertTriggerRuleInvolvedQueuesSelectionControls = (ruleItem, parameters) => {
    let valid = false;

    try {
        const involvedQueuesTitle = ruleItem.querySelector(base.selectors.details.alertDefinition.rule.involvedEntitiesControls.queues.title);
        const involvedQueuesTitleIcon = ruleItem.querySelector(base.selectors.details.alertDefinition.rule.involvedEntitiesControls.queues.titleIcon);
        const involvedQueuesCount = ruleItem.querySelector(base.selectors.details.alertDefinition.rule.involvedEntitiesControls.queues.count);

        const selectedQueues = 
            [...ruleItem.querySelectorAll(base.selectors.details.alertDefinition.rule.involvedEntitiesControls.queues.switch)]
            .filter(item => { return item.checked });

        const selectedQueuesCount = selectedQueues.length;
        involvedQueuesCount.innerHTML = selectedQueuesCount;

        valid = selectedQueuesCount > 0;

        if (valid) {
            parameters.standard.involvedEntities.queues = selectedQueues.map(item => { return item.dataset.id });
        }

        _base.toggleSuccessDangerState(involvedQueuesTitle, valid, true);
        _base.toggleSuccessDangerState(involvedQueuesTitleIcon, valid, true);
        _base.toggleSuccessDangerState(involvedQueuesCount, valid);
    } catch (error) {
        console.log(error);
    }

    return valid;
};

const create = async () => {
    const form = (currentMode === 'add' ? base.elements.addForm : document.querySelector(base.selectors.editForm));
    const activeStepContent = form.querySelector(base.selectors.activeStepContent);
    try {
        _base.renderLoader(activeStepContent);
        return new Promise((resolve, reject) => {
            resolve(
                details.currentAlertTrigger.save(
                    details.currentWatchedAutomatedProcess.id
                ).then(response => {
                    return new Promise((resolve, reject) => {
                        resolve(
                            configuration.getAlertTriggersCreationConfirmation(details.currentAlertTrigger.id).then(response => {
                                updateTable();
                                layoutController.update(configuration.layout);
                                activeStepContent.innerHTML = response.data;
                                _base.clearLoader(activeStepContent);
                            })
                        );
                    });
                })
            );
        });
    } catch (error) {
        _base.clearLoader(activeStepContent);
        console.log(error);
    }
};

const update = async () => {
    const form = document.querySelector(base.selectors.editForm);
    const activeStepContent = form.querySelector(base.selectors.activeStepContent);
    try {
        _base.renderLoader(activeStepContent);
        return new Promise((resolve, reject) => {
            resolve(
                details.currentAlertTrigger.update().then(response => {
                    return new Promise((resolve, reject) => {
                        resolve(
                            configuration.getAlertTriggersCreationConfirmation(details.currentAlertTrigger.id).then(response => {
                                updateTable().then(response => {
                                    $(base.selectors.table).DataTable().row(`tr[data-id="${details.currentAlertTrigger.id}"]`)
                                        .node().classList.add('is-selected');
                                });
                                layoutController.update(configuration.layout);
                                activeStepContent.innerHTML = response.data;
                                _base.clearLoader(activeStepContent);
                            })
                        );
                    });
                })
            );
        });
    } catch (error) {
        _base.clearLoader(activeStepContent);
        console.log(error);
    }
};

const updateTable = async () => {
    const table = document.querySelector(base.selectors.table);
    try {
        _base.renderLoader(table);
        return new Promise((resolve, reject) => {
            resolve(
                configuration.updateAlertTriggersTable().then(res => {
                    view.updateTable(configuration.alertTriggersTable);
                    $(base.selectors.table).DataTable()
                        .on('select', loadEditForm);
                    $(base.selectors.table).DataTable()
                        .on('user-select', function (e, dt, type, cell, originalEvent) {
                            if (originalEvent.target.closest('tr').classList.contains('is-selected')) {
                                e.preventDefault();
                            }
                        });
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
        const form = (currentMode === 'add' ? base.elements.addForm : document.querySelector(base.selectors.editForm));
        const formSelector = (currentMode === 'add' ? base.selectors.addForm : base.selectors.editForm);

        steps[0].activate_step(0);
        steps[0].uncomplete_step(0);
        steps[0].uncomplete_step(1);
        steps[0].uncomplete_step(2);
        processSelection.watchedProcessSelect(form).value = 0;
        processSelection.clientSelect(form).value = 0;
        form.querySelector(base.selectors.details.title).value = '';

        $(`${formSelector} ${base.selectors.details.alertDefinition.item}`).each((index, item) => {
            view.details.deleteDefinition(form, item);
        });
        view.details.updateDefinitionsCount(form, 0);
    } catch (error) {
        console.log(error);
    }
};