import toastr from 'toastr';
import bulmaSteps from 'bulma-steps';

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

const processSelection = {
    clientSelect: base.elements.processSelection.clientSelect,
    watchedProcessSelect: base.elements.processSelection.watchedProcessSelect
};

const details = {
    alertDefinitions: {
        list: [],
        find: rank => {
            return details.alertDefinitions.list.find(item => {
                return item.rank === rank;
            });
        },
        remove: rank => {
            details.alertDefinitions.list = details.alertDefinitions.list.filter(item => {
                return item.rank !== rank;
            });
            // change rank of all items when > rank
            details.alertDefinitions.list = details.alertDefinitions.list.map(item => {
                if (item.rank > rank) {
                    item.rank = item.rank - 1;
                }
                return item;
            });
        },
        areValid: () => {
            let valid = true;
            details.alertDefinitions.list.forEach(alertDefinition => {
                valid = valid && alertDefinition.isValid();
            });
            return valid;
        }
    }
};

export const init = () => {
    try {
        setInterval(() => {
            layoutController.update(configuration.layout);
        }, 45000);
        
        let steps = bulmaSteps.attach(base.selectors.steps, {
            onShow: id => {
                switch (id) {
                    case 0:
                        validateProcessSelectionForm();
                        break;
                    case 1:
                        if (!(previousWatchedAutomatedProcess && previousWatchedAutomatedProcess.id === processSelection.watchedProcessSelect.value)) {
                            details.alertDefinitions.list = [];
                            loadDefaultAlertTriggerDetails(processSelection.watchedProcessSelect.value).then(response => {
                                initAlertTriggerDetails();
                            });
                        }
                        break;
                    case 2:
                        loadDefaultAlertTriggerSummary(processSelection.watchedProcessSelect.value);
                        break;
                    case 3:
                        create().then(response => {
                            initAlertTriggerConfirmation();
                        });
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
        
        initProcessSelection();
    } catch (error) {
        console.log(`Unable to init alert trigger controller: ${error}`);
    }
};

const initProcessSelection = () => {
    try {
        processSelection.clientSelect.addEventListener('change', async (e) => {
            await loadProcesses(e);
            validateProcessSelectionForm();
        });
        processSelection.watchedProcessSelect.addEventListener('change', async (e) => {
            validateProcessSelectionForm(e);

            if (
                previousWatchedAutomatedProcess
                && processSelection.watchedProcessSelect.value !== '0'
                && previousWatchedAutomatedProcess.id !== processSelection.watchedProcessSelect.value
                && details.alertDefinitions.list.length > 0
            ) {
                const alert = await _base.swalWithBulmaButtons.fire({
                    title: 'New automated watched process selection detected',
                    text: `You selected a new automated watched process but you already defined ${details.alertDefinitions.list.length}
                    trigger${details.alertDefinitions.list.length > 1 ? 's' : ''} for process ${previousWatchedAutomatedProcess.data.name}
                    of client ${previousClient.data.name}.
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
    try {
        const title = document.querySelector(base.selectors.details.title);
        title.addEventListener('change', validateTriggerDetailsForm);
        title.addEventListener('keyup', validateTriggerDetailsForm);

        document.querySelector(base.selectors.details.alertDefinition.section).addEventListener('click', (e) => {
            const target = e.target;

            if (target.matches(`${base.selectors.details.alertDefinition.addButton}, ${base.selectors.details.alertDefinition.addButtonChildren}`)) {
                addDefaultAlertTriggerDefinition();
            }

            if (target.matches(`${base.selectors.details.alertDefinition.deleteButton}, ${base.selectors.details.alertDefinition.deleteButtonChildren}`)) {
                const item = target.closest(base.selectors.details.alertDefinition.item);
                const rank = parseInt(item.dataset.rank);
                view.details.deleteDefinition(item);
                details.alertDefinitions.remove(rank);
                view.details.updateDefinitionsCount(details.alertDefinitions.list.length);
            }

            if (target.matches(`${base.selectors.details.alertDefinition.rule.addButton}, ${base.selectors.details.alertDefinition.rule.addButtonChildren}`)) {
                const item = target.closest(base.selectors.details.alertDefinition.item);
                const rank = parseInt(item.dataset.rank);
                let alertDefinition = details.alertDefinitions.find(rank);
                addDefaultAlertTriggerRule(processSelection.watchedProcessSelect.value, alertDefinition, item);
            }

            if (target.matches(`${base.selectors.details.alertDefinition.rule.deleteButton}, ${base.selectors.details.alertDefinition.rule.deleteButtonChildren}`)) {
                const alertDefinitionItem = target.closest(base.selectors.details.alertDefinition.item);
                const alertDefinitionRank = parseInt(alertDefinitionItem.dataset.rank);
                const ruleItem = target.closest(base.selectors.details.alertDefinition.rule.item);
                const ruleItemRank = parseInt(ruleItem.dataset.rank);
                let alertDefinition = details.alertDefinitions.find(alertDefinitionRank);
                view.details.deleteRule(alertDefinitionItem, ruleItem);
                alertDefinition.removeRule(ruleItemRank);
            }
        });

        document.querySelector(base.selectors.details.alertDefinition.section).addEventListener('change', (e) => {
            const target = e.target;

            if (target.matches(base.selectors.details.alertDefinition.levelSelect)) {
                const level = target.value;
                const item = target.closest(base.selectors.details.alertDefinition.item);
                const rank = parseInt(item.dataset.rank);
                view.details.updateDefinitionLevel(item, level);
                let alertDefinition = details.alertDefinitions.find(rank);
                alertDefinition.level = level;
            }

            if (target.matches(base.selectors.details.alertDefinition.rule.typeSelect)) {
                const type = target.value;
                const alertDefinitionItem = target.closest(base.selectors.details.alertDefinition.item);
                const alertDefinitionRank = parseInt(alertDefinitionItem.dataset.rank);
                const ruleItem = target.closest(base.selectors.details.alertDefinition.rule.item);
                const ruleItemRank = parseInt(ruleItem.dataset.rank);
                const rule = details.alertDefinitions.find(alertDefinitionRank).findRule(ruleItemRank);
                
                updateAlertTriggerRuleType(processSelection.watchedProcessSelect.value, rule, ruleItem, type).then(response => {
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
                const alertDefinition = details.alertDefinitions.find(alertDefinitionRank);
                const rule = alertDefinition.findRule(ruleItemRank);

                validateAlertTriggerRuleForm(rule, ruleItem);
            }
        });

        document.querySelector(base.selectors.details.alertDefinition.section).addEventListener('keyup', (e) => {
            const target = e.target;

            if (target.matches(base.selectors.details.alertDefinition.rule.parameter)) {
                const alertDefinitionItem = target.closest(base.selectors.details.alertDefinition.item);
                const alertDefinitionRank = parseInt(alertDefinitionItem.dataset.rank);
                const ruleItem = target.closest(base.selectors.details.alertDefinition.rule.item);
                const ruleItemRank = parseInt(ruleItem.dataset.rank);
                const alertDefinition = details.alertDefinitions.find(alertDefinitionRank);
                const rule = alertDefinition.findRule(ruleItemRank);

                validateAlertTriggerRuleForm(rule, ruleItem);
            }
        });
    } catch (error) {
        console.log(`Unable to init trigger details step: ${error}`)
    }
};

const initAlertTriggerConfirmation = () => {
    try {
        const activeStepContent = document.querySelector(base.selectors.activeStepContent);
        activeStepContent.querySelector(base.selectors.confirmation.notification).addEventListener('click', (e) => {
            const target = e.target;

            if (target.matches(`${base.selectors.confirmation.activateButton}, ${base.selectors.confirmation.activateButtonChildren}`)) {
                const button = target.closest(base.selectors.confirmation.activateButton);
                _base.renderLoader(button);
                try {
                    const alertTrigger = new AlertTrigger(button.dataset.id);
                    alertTrigger.activate().then(response => {
                        updateTable();
                        _base.clearLoader(button);
                        button.disabled = true;
                    });
                } catch (error) {
                    _base.clearLoader(button);
                    console.log(error);
                }
            }
        });
    } catch (error) {
        console.log(`Unable to init trigger confirmation step: ${error}`)
    }
};

const validateProcessSelectionForm = () => {
    let errors = [];

    const clientSelectValid = !(processSelection.clientSelect.value === "0");
    _base.toggleSuccessDangerState(processSelection.clientSelect.parentNode, clientSelectValid);
    if (!clientSelectValid) {
        errors.push('You need to select a Client');
    }
    
    const watchedProcessSelectValid = !(processSelection.watchedProcessSelect.value === "0");
    if (!processSelection.watchedProcessSelect.disabled) {
        _base.toggleSuccessDangerState(processSelection.watchedProcessSelect.parentNode, watchedProcessSelectValid);
        if (!watchedProcessSelectValid) {
            errors.push('You need to select a Watched process');
        }
    } else {
        _base.removeStates(processSelection.watchedProcessSelect.parentNode);
    }

    return errors;
};

const validateTriggerDetailsForm = () => {
    let errors = [];

    const title = document.querySelector(base.selectors.details.title);
    const titleValid = !(title.value.trim() === '');
    _base.toggleSuccessDangerState(title, titleValid);
    if (!titleValid) {
        errors.push('You need to enter a title');
    }

    if (details.alertDefinitions.list.length === 0) {
        errors.push('You need to add at least 1 alert definition');
    }

    const alertDefinitionsValid = details.alertDefinitions.areValid();
    if (!alertDefinitionsValid) {
        errors.push('You need to fix errors on all alert definitions');
    }

    return errors;
};

const loadProcesses = async (e) => {
    const activeStepContent = document.querySelector(base.selectors.activeStepContent);
    try {
        _base.renderLoader(activeStepContent);
        _base.removeSelectOptions(processSelection.watchedProcessSelect, true);
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
            processSelection.watchedProcessSelect.disabled = true;
            _base.clearLoader(activeStepContent);
        }
    } catch (error) {
        _base.clearLoader(activeStepContent);
        console.log(error);
    }
};

let previousWatchedAutomatedProcess;
let previousClient;
const loadDefaultAlertTriggerDetails = async (watchedAutomatedProcessId) => {
    const activeStepContent = document.querySelector(base.selectors.activeStepContent);
    try {
        _base.renderLoader(activeStepContent);
        return new Promise((resolve, reject) => {
            resolve(
                configuration.getAlertTriggersDefaultDetails(watchedAutomatedProcessId).then(async (response) => {
                    view.updateActiveStepContent(response.data);
                    
                    previousWatchedAutomatedProcess = new WatchedAutomatedProcess(watchedAutomatedProcessId);
                    await previousWatchedAutomatedProcess.get();
                    previousClient = new Client(previousWatchedAutomatedProcess.data.client_id);
                    await previousClient.get();
                    
                    _base.clearLoader(activeStepContent);
                })
            );
        });
    } catch (error) {
        _base.clearLoader(activeStepContent);
        console.log(error);
    }
};

let summaryAlertTrigger;
const loadDefaultAlertTriggerSummary = async (watchedAutomatedProcessId) => {
    const activeStepContent = document.querySelector(base.selectors.activeStepContent);
    try {
        _base.renderLoader(activeStepContent);
        return new Promise((resolve, reject) => {
            resolve(
                configuration.getAlertTriggersDefaultSummary(
                    watchedAutomatedProcessId,
                    document.querySelector(base.selectors.details.title).value,
                    details.alertDefinitions.list
                ).then(async (response) => {
                    view.updateActiveStepContent(response.data.view);
                    summaryAlertTrigger = response.data.alertTrigger;

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
    const activeStepContent = document.querySelector(base.selectors.activeStepContent);
    try {
        _base.renderLoader(activeStepContent);
        return new Promise((resolve, reject) => {
            const rank = details.alertDefinitions.list.length + 1;
            resolve(
                configuration.getAlertTriggersDefaultDefinition(rank).then(response => {
                    view.details.addDefinition(response.data);
                    view.details.updateDefinitionsCount(rank);

                    const alertDefinition = new AlertTriggerDefinition(rank);
                    details.alertDefinitions.list.push(alertDefinition);

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
    const activeStepContent = document.querySelector(base.selectors.activeStepContent);
    try {
        _base.renderLoader(activeStepContent);
        return new Promise((resolve, reject) => {
            const rank = alertDefinition.rules.length + 1;
            resolve(
                configuration.getAlertTriggersDefaultRule(watchedAutomatedProcessId, rank).then(response => {
                    view.details.addRule(alertDefinitionItem, response.data);
                    
                    const alertRule = new AlertTriggerRule(alertDefinition.rank, rank);
                    alertDefinition.rules.push(alertRule);
                    
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
    const activeStepContent = document.querySelector(base.selectors.activeStepContent);
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
        }
        /* else if (type === 'kibana-metric-visualization') {
            valid = validateAlertTriggerKibanaMetricVisualization(rule, ruleItem);
        }*/

        rule.valid = valid;
        view.details.updateDefinitionValidity(alertDefinitionItem, details.alertDefinitions.find(rule.definitionRank).isValid());

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
        //_base.toggleFormControlTooltip(maximalPercentageInput, maximalPercentageInputValid);
        
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
        //_base.toggleFormControlTooltip(maximalPercentageInput, maximalPercentageInputValid);
        
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
        //_base.toggleFormControlTooltip(searchQueryInput, searchQueryInputValid);

        const lowerCountInputValid = lowerCountInput.value.trim() !== '' && _base.isNormalInteger(lowerCountInput.value);
        _base.toggleSuccessDangerState(lowerCountInput, lowerCountInputValid);
        //_base.toggleFormControlTooltip(lowerCountInput, lowerCountInputValid);

        const higherCountInputValid = higherCountInput.value.trim() !== '' && _base.isNormalInteger(higherCountInput.value)
            && lowerCountInputValid && parseInt(lowerCountInput.value) < parseInt(higherCountInput.value);
        _base.toggleSuccessDangerState(higherCountInput, higherCountInputValid);
        //_base.toggleFormControlTooltip(higherCountInput, higherCountInputValid);
        
        if (lowerCountInputValid && higherCountInputValid) {
            parameters.specific = {
                searchQuery: searchQueryInput.value.trim(),
                lowerCount: parseInt(lowerCountInput.value),
                higherCount: parseInt(higherCountInput.value)
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

/*
const validateAlertTriggerKibanaMetricVisualization = (rule, ruleItem) => {
    let valid = false;
    let parameters = {
        specific: {},
        standard: {
            timeSlot: {},
            involvedEntities: {}
        }
    };

    try {
        const metricVisualizationSelect = ruleItem.querySelector(base.selectors.details.alertDefinition.rule.kibanaMetricVisualizationControls.metricVisualizationSelect);
        const lowerCountInput = ruleItem.querySelector(base.selectors.details.alertDefinition.rule.kibanaMetricVisualizationControls.lowerCountInput);
        const higherCountInput = ruleItem.querySelector(base.selectors.details.alertDefinition.rule.kibanaMetricVisualizationControls.higherCountInput);

        const metricVisualizationSelectValid = metricVisualizationSelect.value !== 'none';
        _base.toggleSuccessDangerState(metricVisualizationSelect.parentNode, metricVisualizationSelectValid);
        //_base.toggleFormControlTooltip(metricVisualizationSelect.parentNode, metricVisualizationSelectValid);

        const lowerCountInputValid = lowerCountInput.value.trim() !== '' && _base.isNormalInteger(lowerCountInput.value);
        _base.toggleSuccessDangerState(lowerCountInput, lowerCountInputValid);
        //_base.toggleFormControlTooltip(lowerCountInput, lowerCountInputValid);

        const higherCountInputValid = higherCountInput.value.trim() !== '' && _base.isNormalInteger(higherCountInput.value)
            && lowerCountInputValid && parseInt(lowerCountInput.value) < parseInt(higherCountInput.value);
        _base.toggleSuccessDangerState(higherCountInput, higherCountInputValid);
        //_base.toggleFormControlTooltip(higherCountInput, higherCountInputValid);
        
        if (lowerCountInputValid && higherCountInputValid) {
            parameters.specific = {
                metricVisualization: metricVisualizationSelect.value,
                lowerCount: parseInt(lowerCountInput.value),
                higherCount: parseInt(higherCountInput.value)
            };
        }
        
        const timeSlotInputValid = validateAlertTriggerRuleTimeSlotControls(ruleItem, parameters);
        const relativeTimeSlotInputValid = validateAlertTriggerRuleRelativeTimeSlotControls(ruleItem, parameters);
        const involvedProcessesSelectionValid = validateAlertTriggerRuleInvolvedProcessesSelectionControls(ruleItem, parameters);
        const involvedRobotsSelectionValid = validateAlertTriggerRuleInvolvedRobotsSelectionControls(ruleItem, parameters);

        rule.parameters = parameters;

        valid = metricVisualizationSelectValid && lowerCountInputValid && higherCountInputValid && timeSlotInputValid
            && relativeTimeSlotInputValid && involvedProcessesSelectionValid && involvedRobotsSelectionValid;
    } catch (error) {
        console.log(error);
    }

    return valid;
};
*/

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
        //_base.toggleFormControlTooltip(timeSlotInput, valid);
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
        //_base.toggleFormControlTooltip(relativeTimeSlotInput, valid);
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
    const activeStepContent = document.querySelector(base.selectors.activeStepContent);
    try {
        _base.renderLoader(activeStepContent);
        return new Promise((resolve, reject) => {
            const alertTrigger = new AlertTrigger();
            resolve(
                alertTrigger.save(
                    summaryAlertTrigger.watched_automated_process_id,
                    summaryAlertTrigger.title,
                    summaryAlertTrigger.definitions
                ).then(response => {
                    return new Promise((resolve, reject) => {
                        resolve(
                            configuration.getAlertTriggersCreationConfirmation(alertTrigger.id).then(response => {
                                updateTable();
                                layoutController.update(configuration.layout);
                                view.updateActiveStepContent(response.data);
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
        
    } catch (error) {
        console.log(error);
    }
};