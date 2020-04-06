import toastr from 'toastr';
import bulmaCalendar from 'bulma-calendar';

import Configuration from '../../../models/Configuration';
import WatchedAutomatedProcess from '../../../models/WatchedAutomatedProcess';
import Client from '../../../models/Client';

import * as _base from '../../base';
import * as base from './base';
import * as view from './view';

import * as layoutController from '../../layout/index';

const configuration = new Configuration('configuration.watched-automated-process.index');

const clientSelect = document.querySelector(base.selectors.clientSelect);
const nameInput = document.querySelector(base.selectors.nameInput);
const codeInput = document.querySelector(base.selectors.codeInput);
const operationalHandbookPageURLInput = document.querySelector(base.selectors.operationalHandbookPageURLInput);
const kibanaDashboardURLInput = document.querySelector(base.selectors.kibanaDashboardURLInput);
const additionalInformationTextarea = document.querySelector(base.selectors.additionalInformationTextarea);
const runningPeriodSectionTitle = base.elements.runningPeriodSectionTitle;
const runningPeriodSectionTitleIcon = base.elements.runningPeriodSectionTitleIcon;
const runningPeriodMondayCheckbox = document.querySelector(base.selectors.runningPeriodMondayCheckbox);
const runningPeriodTuesdayCheckbox = document.querySelector(base.selectors.runningPeriodTuesdayCheckbox);
const runningPeriodWednesdayCheckbox = document.querySelector(base.selectors.runningPeriodWednesdayCheckbox);
const runningPeriodThursdayCheckbox = document.querySelector(base.selectors.runningPeriodThursdayCheckbox);
const runningPeriodFridayCheckbox = document.querySelector(base.selectors.runningPeriodFridayCheckbox);
const runningPeriodSaturdayCheckbox = document.querySelector(base.selectors.runningPeriodSaturdayCheckbox);
const runningPeriodSundayCheckbox = document.querySelector(base.selectors.runningPeriodSundayCheckbox);
const runningPeriodCalendar = bulmaCalendar.attach(base.selectors.runnningPeriodCalendar, {
    type: 'time',
    lang: 'en',
    isRange: true,
    headerPosition: 'bottom',
    labelFrom: 'From',
    labelTo: 'To',
    timeFormat: 'HH:mm',
    showFooter: true
});
const involvedProcessesSectionTitle = base.elements.involvedProcessesSectionTitle;
const involvedProcessesSectionTitleIcon = base.elements.involvedProcessesSectionTitleIcon;
const involvedProcessesCount = base.elements.involvedProcessesCount;
const involvedRobotsSectionTitle = base.elements.involvedRobotsSectionTitle;
const involvedRobotsSectionTitleIcon = base.elements.involvedRobotsSectionTitleIcon;
const involvedRobotsCount = base.elements.involvedRobotsCount;
const involvedQueuesSectionTitle = base.elements.involvedQueuesSectionTitle;
const involvedQueuesSectionTitleIcon = base.elements.involvedQueuesSectionTitleIcon;
const involvedQueuesCount = base.elements.involvedQueuesCount;
const createButton = base.elements.createButton;
const resetButton = base.elements.resetButton;

export const init = () => {
    try {
        setInterval(() => {
            layoutController.update(configuration.layout);
        }, 45000);
        
        base.elements.addForm.addEventListener('keyup', validateForm);
        base.elements.addForm.addEventListener('change', validateForm);
        document.querySelector(base.selectors.runningPeriodCalendar).bulmaCalendar.on('select clear', validateForm);
        document.querySelector(_base.selectors.dateTimeFooterCancelButton).addEventListener('click', validateForm);

        clientSelect.addEventListener('change', async (e) => {
            loadProcesses(e);
            loadRobots(e);
            loadQueues(e);
        });

        base.elements.addForm.addEventListener('click', async (e) => {
            if (e.target.matches(`${base.selectors.createButton}, ${base.selectors.createButtonChildren}`) && !createButton.disabled) {
                create().then(res => {
                    toastr.success('Watched automated process successfully added!', null, {
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
        console.log(`Unable to init watched automated process controller: ${error}`);
    }
};

const validateForm = () => {
    const clientSelectValid = !(clientSelect.value === "0");
    _base.toggleSuccessDangerState(clientSelect.parentNode, clientSelectValid);

    const nameInputValid = !(nameInput.value.trim() === '');
    _base.toggleSuccessDangerState(nameInput, nameInputValid);

    const codeInputValid = !(codeInput.value.trim() === '');
    _base.toggleSuccessDangerState(codeInput, codeInputValid);
    
    const operationalHandbookPageURLInputValid = !(operationalHandbookPageURLInput.value.trim() !== '' && !_base.validURL(operationalHandbookPageURLInput.value));
    _base.toggleSuccessDangerState(operationalHandbookPageURLInput, operationalHandbookPageURLInputValid);

    const kibanaDashboardURLInputValid = !(kibanaDashboardURLInput.value.trim() !== '' && !_base.validURL(kibanaDashboardURLInput.value));
    _base.toggleSuccessDangerState(kibanaDashboardURLInput, kibanaDashboardURLInputValid);

    const runningPeriodDaysValid = runningPeriodMondayCheckbox.checked || runningPeriodTuesdayCheckbox.checked ||
        runningPeriodWednesdayCheckbox.checked || runningPeriodThursdayCheckbox.checked ||
        runningPeriodFridayCheckbox.checked || runningPeriodSaturdayCheckbox.checked ||
        runningPeriodSundayCheckbox.checked;

    const calendar = document.querySelector(base.selectors.runningPeriodCalendar);
    const calendarFrom = calendar.parentNode.querySelector(_base.selectors.dateTimeCalendarFromInput);
    const calendarTo = calendar.parentNode.querySelector(_base.selectors.dateTimeCalendarToInput);
    const runningPeriodCalendarValid = (calendarFrom.value.trim() !== '' && calendarTo.value.trim() !== '');
    const runningPeriodCalendarWrapper = calendar.closest(_base.selectors.dateTimeCalendarWrapper);
    
    _base.toggleSuccessDangerState(runningPeriodCalendarWrapper, runningPeriodCalendarValid);
    _base.toggleSuccessDangerState(runningPeriodSectionTitle, runningPeriodDaysValid && runningPeriodCalendarValid, true);
    _base.toggleSuccessDangerState(runningPeriodSectionTitleIcon, runningPeriodDaysValid && runningPeriodCalendarValid, true);

    let selectedProcessesCount = 0
    if ($.fn.dataTable.isDataTable(base.selectors.involvedProcessesTable)) {
        selectedProcessesCount = $(base.selectors.involvedProcessesTable).DataTable().rows({ selected: true }).count();
    }
    involvedProcessesCount.innerHTML = selectedProcessesCount;
    const involvedProcessesTableValid = selectedProcessesCount > 0;
    _base.toggleSuccessDangerState(involvedProcessesSectionTitle, involvedProcessesTableValid, true);
    _base.toggleSuccessDangerState(involvedProcessesSectionTitleIcon, involvedProcessesTableValid, true);
    _base.toggleSuccessDangerState(involvedProcessesCount, involvedProcessesTableValid);

    let selectedRobotsCount = 0
    if ($.fn.dataTable.isDataTable(base.selectors.involvedRobotsTable)) {
        selectedRobotsCount = $(base.selectors.involvedRobotsTable).DataTable().rows({ selected: true }).count();
    }
    involvedRobotsCount.innerHTML = selectedRobotsCount;
    const involvedRobotsTableValid = selectedRobotsCount > 0;
    _base.toggleSuccessDangerState(involvedRobotsSectionTitle, involvedRobotsTableValid, true);
    _base.toggleSuccessDangerState(involvedRobotsSectionTitleIcon, involvedRobotsTableValid, true);
    _base.toggleSuccessDangerState(involvedRobotsCount, involvedRobotsTableValid);

    let selectedQueuesCount = 0
    if ($.fn.dataTable.isDataTable(base.selectors.involvedQueuesTable)) {
        selectedQueuesCount = $(base.selectors.involvedQueuesTable).DataTable().rows({ selected: true }).count();
    }
    involvedQueuesCount.innerHTML = selectedQueuesCount;
    const involvedQueuesTableValid = /*selectedQueuesCount > 0*/ true;
    _base.toggleSuccessDangerState(involvedQueuesSectionTitle, involvedQueuesTableValid, true);
    _base.toggleSuccessDangerState(involvedQueuesSectionTitleIcon, involvedQueuesTableValid, true);
    _base.toggleSuccessDangerState(involvedQueuesCount, involvedQueuesTableValid);

    _base.toggleSuccessDangerState(additionalInformationTextarea, true);
    
    const formValid = nameInputValid && codeInputValid && clientSelectValid &&
        operationalHandbookPageURLInputValid && kibanaDashboardURLInputValid &&
        runningPeriodDaysValid && runningPeriodCalendarValid && involvedProcessesTableValid &&
        involvedRobotsTableValid && involvedQueuesTableValid;

    createButton.disabled = !formValid;
};

const loadProcesses = async (e) => {
    try {
        _base.renderLoader(base.elements.involvedProcessesSection);
        const id = e.target.value.trim();
        if (id !== "0") {
            const client = new Client(id);
            client.getProcessesFromOrchestrator().then(response => {
                const data = response.data;
                document.querySelector(base.selectors.involvedProcessesTable).classList.add('table');
                let selected = [];
                if (data.error) {
                    toastr.error(`Unable to load processes: ${data.error}`, null, {
                        positionClass: 'toast-bottom-right'
                    });
                    if ($.fn.dataTable.isDataTable(base.selectors.involvedProcessesTable)) {
                        $(base.selectors.involvedProcessesTable).DataTable().clear().draw();
                    }
                } else {
                    if ($.fn.dataTable.isDataTable(base.selectors.involvedProcessesTable)) {
                        $(base.selectors.involvedProcessesTable).DataTable().destroy();
                    }
                    $(base.selectors.involvedProcessesTable).DataTable({
                        select: {
                            style: 'multi',
                            className: 'is-selected',
                            info: false
                        },
                        rowId: 'Id',
                        data: data,
                        columns: [
                            { title: 'Name', data: 'ProcessKey' },
                            { title: 'Version', data: 'ProcessVersion' },
                            { title: 'Environment', data: 'EnvironmentName' }
                        ]
                    }).on('select deselect', () => {
                        validateForm();
                        refreshInvolvedRobotsTable();
                    });
                    validateForm();
                }
                _base.clearLoader(base.elements.involvedProcessesSection);
            });
        } else {
            if ($.fn.dataTable.isDataTable(base.selectors.involvedProcessesTable)) {
                $(base.selectors.involvedProcessesTable).DataTable().clear().draw();
            }
            _base.clearLoader(base.elements.involvedProcessesSection);
        }
    } catch (error) {
        _base.clearLoader(base.elements.involvedProcessesSection);
        console.log(error);
    }
};

let involvedRobotsTableData;

const loadRobots = async (e) => {
    try {
        _base.renderLoader(base.elements.involvedRobotsSection);
        const id = e.target.value.trim();
        if (id !== "0") {
            const client = new Client(id);
            client.getRobotsFromOrchestrator().then(response => {
                const data = response.data;
                document.querySelector(base.selectors.involvedRobotsTable).classList.add('table');
                let selected = [];
                if (data.error) {
                    toastr.error(`Unable to load robots: ${data.error}`, null, {
                        positionClass: 'toast-bottom-right'
                    });
                    if ($.fn.dataTable.isDataTable(base.selectors.involvedRobotsTable)) {
                        $(base.selectors.involvedRobotsTable).DataTable().clear().draw();
                    }
                } else {
                    if ($.fn.dataTable.isDataTable(base.selectors.involvedRobotsTable)) {
                        $(base.selectors.involvedRobotsTable).DataTable().destroy();
                    }
                    $(base.selectors.involvedRobotsTable).DataTable({
                        select: {
                            style: 'multi',
                            className: 'is-selected',
                            info: false
                        },
                        rowId: 'Id',
                        data: data,
                        columns: [
                            { title: 'Name', data: 'Name' },
                            { title: 'Description', data: 'Description' },
                            { title: 'Username', data: 'Username' },
                            { title: 'Type', data: 'Type' },
                            { title: 'Environments', data: 'RobotEnvironments' }
                        ]
                    }).on('select deselect', validateForm);
                    involvedRobotsTableData = data;
                    validateForm();
                }
                _base.clearLoader(base.elements.involvedRobotsSection);
            });
        } else {
            if ($.fn.dataTable.isDataTable(base.selectors.involvedRobotsTable)) {
                $(base.selectors.involvedRobotsTable).DataTable().clear().draw();
            }
            _base.clearLoader(base.elements.involvedRobotsSection);
        }
    } catch (error) {
        _base.clearLoader(base.elements.involvedRobotsSection);
        console.log(error);
    }
};

const refreshInvolvedRobotsTable = () => {
    try {
        _base.renderLoader(base.elements.involvedProcessesTable);
        if ($.fn.dataTable.isDataTable(base.selectors.involvedProcessesTable)) {
            const involvedProcessesRows = $(base.selectors.involvedProcessesTable).DataTable().rows({ selected: true }).data();
            let involvedProcessesEnvironments = [];
            for (let i = 0; i < involvedProcessesRows.length; i++) {
                const environment = involvedProcessesRows[i]['EnvironmentName'];
                if (involvedProcessesEnvironments.indexOf(environment) < 0) {
                    involvedProcessesEnvironments.push(environment);
                }
            }
            if ($.fn.dataTable.isDataTable(base.selectors.involvedRobotsTable)) {
                const table = $(base.selectors.involvedRobotsTable).DataTable();
                const previouslySelectedRows = table.rows({ selected: true }).data();
                table.clear().rows.add(involvedRobotsTableData);
                if (involvedProcessesRows.length > 0) {
                    table.rows((index, data, node) => {
                        let environments = data['RobotEnvironments'].split(',').map(item => {
                            return item.trim();
                        });
                        return environments.some(item => involvedProcessesEnvironments.indexOf(item) < 0);
                    }).remove();
                }
                table.draw();
                // reselect previously selected rows
                table.rows((index, data, node) => {
                    return previouslySelectedRows.map(item => { return item['Id']; }).indexOf(data['Id']) >= 0;
                }).select();
            }
            validateForm();
        }
        _base.clearLoader(base.elements.involvedRobotsTable);
    } catch (error) {
        _base.clearLoader(base.elements.involvedRobotsTable);
        console.log(error);
    }
};

const loadQueues = async (e) => {
    try {
        _base.renderLoader(base.elements.involvedQueuesSection);
        const id = e.target.value.trim();
        if (id !== "0") {
            const client = new Client(id);
            client.getQueuesFromOrchestrator().then(response => {
                const data = response.data;
                document.querySelector(base.selectors.involvedQueuesTable).classList.add('table');
                let selected = [];
                if (data.error) {
                    toastr.error(`Unable to load queues: ${data.error}`, null, {
                        positionClass: 'toast-bottom-right'
                    });
                    if ($.fn.dataTable.isDataTable(base.selectors.involvedQueuesTable)) {
                        $(base.selectors.involvedQueuesTable).DataTable().clear().draw();
                    }
                } else {
                    if ($.fn.dataTable.isDataTable(base.selectors.involvedQueuesTable)) {
                        $(base.selectors.involvedQueuesTable).DataTable().destroy();
                    }
                    $(base.selectors.involvedQueuesTable).DataTable({
                        select: {
                            style: 'multi',
                            className: 'is-selected',
                            info: false
                        },
                        rowId: 'Id',
                        data: data,
                        columns: [
                            { title: 'Name', data: 'Name' },
                            { title: 'Description', data: 'Description' }
                        ]
                    }).on('select deselect', validateForm);
                    validateForm();
                }
                _base.clearLoader(base.elements.involvedQueuesSection);
            });
        } else {
            if ($.fn.dataTable.isDataTable(base.selectors.involvedQueuesTable)) {
                $(base.selectors.involvedQueuesTable).DataTable().clear().draw();
            }
            _base.clearLoader(base.elements.involvedQueuesSection);
        }
    } catch (error) {
        _base.clearLoader(base.elements.involvedQueuesSection);
        console.log(error);
    }
};

const create = async () => {
    const addForm = base.elements.addForm;
    try {
        _base.renderLoader(addForm);

        const involvedProcessesRows = $(base.selectors.involvedProcessesTable).DataTable().rows({ selected: true }).data();
        let involvedProcesses = [];
        for (let i = 0; i < involvedProcessesRows.length; i++) {
            const involvedProcess = {
                name: involvedProcessesRows[i]['ProcessKey'],
                description: involvedProcessesRows[i]['Description'],
                version: involvedProcessesRows[i]['ProcessVersion'],
                external_id: involvedProcessesRows[i]['Id'],
                environment_name: involvedProcessesRows[i]['EnvironmentName'],
                external_environment_id: involvedProcessesRows[i]['EnvironmentId']
            }
            involvedProcesses.push(involvedProcess);
        }

        const involvedRobotsRows = $(base.selectors.involvedRobotsTable).DataTable().rows({ selected: true }).data();
        let involvedRobots = [];
        for (let i = 0; i < involvedRobotsRows.length; i++) {
            const involvedRobot = {
                name: involvedRobotsRows[i]['Name'],
                machine_name: involvedRobotsRows[i]['MachineName'],
                description: involvedRobotsRows[i]['Description'],
                username: involvedRobotsRows[i]['Username'],
                type: involvedRobotsRows[i]['Type'],
                external_id: involvedRobotsRows[i]['Id']
            }
            involvedRobots.push(involvedRobot);
        }

        const involvedQueuesRows = $(base.selectors.involvedQueuesTable).DataTable().rows({ selected: true }).data();
        let involvedQueues = [];
        for (let i = 0; i < involvedQueuesRows.length; i++) {
            const involvedQueue = {
                name: involvedQueuesRows[i]['Name'],
                description: involvedQueuesRows[i]['Description'],
                external_id: involvedQueuesRows[i]['Id']
            }
            involvedQueues.push(involvedQueue);
        }
        
        return new Promise((resolve, reject) => {
            const watchedAutomatedProcess = new WatchedAutomatedProcess();
            const calendar = document.querySelector(base.selectors.runningPeriodCalendar).bulmaCalendar;
            resolve(
                watchedAutomatedProcess.save(
                    clientSelect.value.trim(),
                    nameInput.value.trim(),
                    codeInput.value.trim(),
                    operationalHandbookPageURLInput.value.trim(),
                    kibanaDashboardURLInput.value.trim(),
                    additionalInformationTextarea.value.trim(),
                    runningPeriodMondayCheckbox.checked,
                    runningPeriodTuesdayCheckbox.checked,
                    runningPeriodWednesdayCheckbox.checked,
                    runningPeriodThursdayCheckbox.checked,
                    runningPeriodFridayCheckbox.checked,
                    runningPeriodSaturdayCheckbox.checked,
                    runningPeriodSundayCheckbox.checked,
                    calendar.startTime.toTimeString().split(' ')[0],
                    calendar.endTime.toTimeString().split(' ')[0],
                    involvedProcesses,
                    involvedRobots,
                    involvedQueues
                ).then(res => {
                    resetForm();
                    _base.clearLoader(addForm);
                })
            )
        });
    } catch (error) {
        toastr.error(`Watched automated process not added due to application exception: ${error}`, null, {
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
                configuration.updateWatchedAutomatedProcessesTable().then(res => {
                    view.updateTable(configuration.watchedAutomatedProcessesTable);
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
        document.querySelector(base.selectors.runningPeriodCalendar).bulmaCalendar.clear();
        if ($.fn.dataTable.isDataTable(base.selectors.involvedProcessesTable)) {
            $(base.selectors.involvedProcessesTable).DataTable().clear().draw();
        }
        if ($.fn.dataTable.isDataTable(base.selectors.involvedRobotsTable)) {
            $(base.selectors.involvedRobotsTable).DataTable().clear().draw();
        }
        if ($.fn.dataTable.isDataTable(base.selectors.involvedQueuesTable)) {
            $(base.selectors.involvedQueuesTable).DataTable().clear().draw();
        }
        base.elements.addForm.reset();

        _base.removeStates(clientSelect.parentNode);
        _base.removeStates(nameInput);
        _base.removeStates(codeInput);
        _base.removeStates(operationalHandbookPageURLInput);
        _base.removeStates(kibanaDashboardURLInput);
        _base.removeStates(additionalInformationTextarea);
        _base.removeStates(runningPeriodSectionTitle);
        _base.removeStates(runningPeriodSectionTitleIcon);

        runningPeriodMondayCheckbox.checked = false;
        runningPeriodTuesdayCheckbox.checked = false;
        runningPeriodWednesdayCheckbox.checked = false;
        runningPeriodThursdayCheckbox.checked = false;
        runningPeriodFridayCheckbox.checked = false;
        runningPeriodSaturdayCheckbox.checked = false;
        runningPeriodSundayCheckbox.checked = false;

        const calendar = document.querySelector(base.selectors.runningPeriodCalendar);
        const runningPeriodCalendarWrapper = calendar.closest(_base.selectors.dateTimeCalendarWrapper);

        _base.removeStates(runningPeriodCalendarWrapper);
        _base.removeStates(involvedProcessesSectionTitle);
        _base.removeStates(involvedProcessesSectionTitleIcon);
        _base.removeStates(involvedProcessesCount);
        involvedProcessesCount.innerHTML = 0;
        _base.removeStates(involvedRobotsSectionTitle);
        _base.removeStates(involvedRobotsSectionTitleIcon);
        _base.removeStates(involvedRobotsCount);
        involvedRobotsCount.innerHTML = 0;
        _base.removeStates(involvedQueuesSectionTitle);
        _base.removeStates(involvedQueuesSectionTitleIcon);
        _base.removeStates(involvedQueuesCount);
        involvedQueuesCount.innerHTML = 0;
    } catch (error) {
        console.log(error);
    }
};