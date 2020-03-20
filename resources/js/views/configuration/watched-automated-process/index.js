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
const involvedProcessesCount = base.elements.involvedProcessesCount;
const createButton = base.elements.createButton;
const resetButton = base.elements.resetButton;

export const init = () => {
    try {
        base.elements.addForm.addEventListener('keyup', checkForm);
        base.elements.addForm.addEventListener('change', checkForm);
        document.querySelector(base.selectors.runningPeriodCalendar).bulmaCalendar.on('select clear', checkForm);
        document.querySelector(_base.selectors.dateTimeFooterCancelButton).addEventListener('click', checkForm);

        clientSelect.addEventListener('change', loadScripts);

        base.elements.addForm.addEventListener('click', async (e) => {
            if (e.target.matches(`${base.selectors.createButton}, ${base.selectors.createButtonChildren}`) && !createButton.disabled) {
                create().then(res => {
                    toastr.success('Watched automated process successfully added!', null, {
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
        console.log(`Unable to init watched automated process controller: ${error}`);
    }
};

const checkForm = e => {
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

    let count = 0
    if ($.fn.dataTable.isDataTable(base.selectors.involvedProcessesTable)) {
        count = $(base.selectors.involvedProcessesTable).DataTable().rows({ selected: true }).count();
    }
    involvedProcessesCount.innerHTML = count;
    const involvedProcessesTableValid = count > 0;

    _base.toggleSuccessDangerState(involvedProcessesSectionTitle, involvedProcessesTableValid, true);
    _base.toggleSuccessDangerState(involvedProcessesCount, involvedProcessesTableValid);

    _base.toggleSuccessDangerState(additionalInformationTextarea, true);
    
    const formValid = nameInputValid && codeInputValid && clientSelectValid &&
        operationalHandbookPageURLInputValid && kibanaDashboardURLInputValid &&
        runningPeriodDaysValid && runningPeriodCalendarValid && involvedProcessesTableValid;

    createButton.disabled = !formValid;
};

const loadScripts = async (e) => {
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
                    toastr.error(data.error, null, {
                        positionClass: 'toast-bottom-center'
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
                            { title: 'Name', data: 'ProcessKey'},
                            { title: 'Version', data: 'ProcessVersion'},
                            { title: 'Environment', data: 'EnvironmentName'}
                        ]
                    }).on('select deselect', checkForm);
                    checkForm(null);
                }
                _base.clearLoader(base.elements.involvedProcessesSection);
            });
        } else {
            if ($.fn.dataTable.isDataTable(base.selectors.involvedProcessesTable)) {
                $(base.selectors.involvedProcessesTable).DataTable().clear().draw();
            }
        }
    } catch (error) {
        _base.clearLoader(base.elements.involvedProcessesSection);
        console.log(error);
    }
};

const create = async () => {
    const addForm = base.elements.addForm;
    try {
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
        _base.renderLoader(addForm);
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
                    involvedProcesses
                ).then(res => {
                    resetForm();
                    _base.clearLoader(addForm);
                })
            )
        });
    } catch (error) {
        toastr.error(`Watched automated process not added due to application exception: ${error}`, null, {
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
        base.elements.addForm.reset();
        _base.removeStates(clientSelect.parentNode);
        _base.removeStates(nameInput);
        _base.removeStates(codeInput);
        _base.removeStates(operationalHandbookPageURLInput);
        _base.removeStates(kibanaDashboardURLInput);
        _base.removeStates(additionalInformationTextarea);
        _base.removeStates(runningPeriodSectionTitle);
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
        _base.removeStates(involvedProcessesCount);
        involvedProcessesCount.innerHTML = 0;
    } catch (error) {
        console.log(error);
    }
};