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
        bulmaCalendar.attach(`${base.selectors.addForm} ${base.selectors.runningPeriodCalendar}`, {
            type: 'time',
            lang: 'en',
            isRange: true,
            headerPosition: 'bottom',
            labelFrom: 'From',
            labelTo: 'To',
            timeFormat: 'HH:mm',
            showFooter: true
        });
        base.elements.addForm.querySelector(base.selectors.runningPeriodCalendar).bulmaCalendar.on('select clear', checkForm);
        base.elements.addForm.querySelector(_base.selectors.dateTimeFooterCancelButton).addEventListener('click', checkForm);

        const clientSelect = base.elements.addForm.querySelector(base.selectors.clientSelect);
        clientSelect.addEventListener('change', async (e) => {
            loadProcesses(e);
            loadRobots(e);
            loadQueues(e);
        });

        base.elements.addForm.addEventListener('click', async (e) => {
            const createButton = base.elements.addForm.querySelector(base.selectors.createButton);
            if (e.target.matches(`${base.selectors.createButton}, ${base.selectors.createButtonChildren}`) && !createButton.disabled) {
                create().then(res => {
                    toastr.success('Watched automated process successfully added!', null, {
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
        console.log(`Unable to init watched automated process controller: ${error}`);
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

const loadEditForm = async (e) => {
    try {    
        currentMode = 'edit';

        _base.renderLoader(document.querySelector(base.selectors.table));
        _base.renderLoader(base.elements.formsSection);

        const row = $(base.selectors.table).DataTable().row({ selected: true }).node();
        const id = row.dataset.id;

        const watchedAutomatedProcess = new WatchedAutomatedProcess(id);

        watchedAutomatedProcess.loadEditForm().then(response => {
            view.updateEditFormSection(watchedAutomatedProcess.editForm);
            
            const form = document.querySelector(base.selectors.editForm);
            form.addEventListener('keyup', checkForm);
            form.addEventListener('change', checkForm);

            const startTime = form.querySelector(base.selectors.runningPeriodCalendar).dataset.startTime.split(':');
            const endTime = form.querySelector(base.selectors.runningPeriodCalendar).dataset.endTime.split(':');
            bulmaCalendar.attach(`${base.selectors.editForm} ${base.selectors.runningPeriodCalendar}`, {
                type: 'time',
                lang: 'en',
                isRange: true,
                headerPosition: 'bottom',
                labelFrom: 'From',
                labelTo: 'To',
                timeFormat: 'HH:mm',
                showFooter: true,
                start: new Date(1970, 1, 1, startTime[0], startTime[1], 0),
                end: new Date(1970, 1, 1, endTime[0], endTime[1], 0)
            });
            form.querySelector(base.selectors.runningPeriodCalendar).bulmaCalendar.on('select clear', checkForm);
            form.querySelector(_base.selectors.dateTimeFooterCancelButton).addEventListener('click', checkForm);
            
            const clientSelect = form.querySelector(base.selectors.clientSelect);

            loadProcesses(null, clientSelect.value);
            loadRobots(null, clientSelect.value);
            loadQueues(null, clientSelect.value);
            
            form.addEventListener('click', async (e) => {
                const saveButton = form.querySelector(base.selectors.saveButton);
                if (e.target.matches(`${base.selectors.saveButton}, ${base.selectors.saveButtonChildren}`) && !saveButton.disabled) {
                    update().then(response => {
                        loadAddForm(e);
                        updateTable();
                        layoutController.update(configuration.layout);
                        toastr.success('Watched automated process successfully updated!', null, {
                            positionClass: 'toast-bottom-left'
                        });
                    });
                }
                if (e.target.matches(`${base.selectors.cancelButton}, ${base.selectors.cancelButtonChildren}`)) {
                    loadAddForm(e);
                }
                if (e.target.matches(`${base.selectors.removeButton}, ${base.selectors.removeButtonChildren}`)) {
                    _base.swalWithBulmaButtons.fire({
                        title: 'Watched automated process removal confirmation',
                        text: 'This watched automated process and all its related elements (alert triggers and alerts) will be removed. Are you sure?',
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
                                toastr.success('Watched automated process successfully removed!', null, {
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

const checkForm = () => {
    const form = (currentMode === 'add' ? base.elements.addForm : document.querySelector(base.selectors.editForm));
    const formSelector = (currentMode === 'add' ? base.selectors.addForm : base.selectors.editForm);

    const clientSelect = form.querySelector(base.selectors.clientSelect);
    const nameInput = form.querySelector(base.selectors.nameInput);
    const codeInput = form.querySelector(base.selectors.codeInput);
    const operationalHandbookPageURLInput = form.querySelector(base.selectors.operationalHandbookPageURLInput);
    const kibanaDashboardURLInput = form.querySelector(base.selectors.kibanaDashboardURLInput);
    const additionalInformationTextarea = form.querySelector(base.selectors.additionalInformationTextarea);
    const runningPeriodSectionTitle = form.querySelector(base.selectors.runningPeriodSectionTitle);
    const runningPeriodSectionTitleIcon = form.querySelector(base.selectors.runningPeriodSectionTitleIcon);
    const runningPeriodMondayCheckbox = form.querySelector(base.selectors.runningPeriodMondayCheckbox);
    const runningPeriodTuesdayCheckbox = form.querySelector(base.selectors.runningPeriodTuesdayCheckbox);
    const runningPeriodWednesdayCheckbox = form.querySelector(base.selectors.runningPeriodWednesdayCheckbox);
    const runningPeriodThursdayCheckbox = form.querySelector(base.selectors.runningPeriodThursdayCheckbox);
    const runningPeriodFridayCheckbox = form.querySelector(base.selectors.runningPeriodFridayCheckbox);
    const runningPeriodSaturdayCheckbox = form.querySelector(base.selectors.runningPeriodSaturdayCheckbox);
    const runningPeriodSundayCheckbox = form.querySelector(base.selectors.runningPeriodSundayCheckbox);
    const runningPeriodCalendar = form.querySelector(base.selectors.runningPeriodCalendar).bulmaCalendar;
    const involvedProcessesSectionTitle = form.querySelector(base.selectors.involvedProcessesSectionTitle);
    const involvedProcessesSectionTitleIcon = form.querySelector(base.selectors.involvedProcessesSectionTitleIcon);
    const involvedProcessesCount = form.querySelector(base.selectors.involvedProcessesCount);
    const involvedRobotsSectionTitle = form.querySelector(base.selectors.involvedRobotsSectionTitle);
    const involvedRobotsSectionTitleIcon = form.querySelector(base.selectors.involvedRobotsSectionTitleIcon);
    const involvedRobotsCount = form.querySelector(base.selectors.involvedRobotsCount);
    const involvedQueuesSectionTitle = form.querySelector(base.selectors.involvedQueuesSectionTitle);
    const involvedQueuesSectionTitleIcon = form.querySelector(base.selectors.involvedQueuesSectionTitleIcon);
    const involvedQueuesCount = form.querySelector(base.selectors.involvedQueuesCount);

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

    const calendar = form.querySelector(base.selectors.runningPeriodCalendar);
    const calendarFrom = calendar.parentNode.querySelector(_base.selectors.dateTimeCalendarFromInput);
    const calendarTo = calendar.parentNode.querySelector(_base.selectors.dateTimeCalendarToInput);
    const runningPeriodCalendarValid = (calendarFrom.value.trim() !== '' && calendarTo.value.trim() !== '');
    const runningPeriodCalendarWrapper = calendar.closest(_base.selectors.dateTimeCalendarWrapper);
    
    _base.toggleSuccessDangerState(runningPeriodCalendarWrapper, runningPeriodCalendarValid);
    _base.toggleSuccessDangerState(runningPeriodSectionTitle, runningPeriodDaysValid && runningPeriodCalendarValid, true);
    _base.toggleSuccessDangerState(runningPeriodSectionTitleIcon, runningPeriodDaysValid && runningPeriodCalendarValid, true);

    let selectedProcessesCount = 0
    if ($.fn.dataTable.isDataTable(`${formSelector} ${base.selectors.involvedProcessesTable}`)) {
        selectedProcessesCount = $(`${formSelector} ${base.selectors.involvedProcessesTable}`).DataTable().rows({ selected: true }).count();
    }
    involvedProcessesCount.innerHTML = selectedProcessesCount;
    const involvedProcessesTableValid = selectedProcessesCount > 0;
    _base.toggleSuccessDangerState(involvedProcessesSectionTitle, involvedProcessesTableValid, true);
    _base.toggleSuccessDangerState(involvedProcessesSectionTitleIcon, involvedProcessesTableValid, true);
    _base.toggleSuccessDangerState(involvedProcessesCount, involvedProcessesTableValid);

    let selectedRobotsCount = 0
    if ($.fn.dataTable.isDataTable(`${formSelector} ${base.selectors.involvedRobotsTable}`)) {
        selectedRobotsCount = $(`${formSelector} ${base.selectors.involvedRobotsTable}`).DataTable().rows({ selected: true }).count();
    }
    involvedRobotsCount.innerHTML = selectedRobotsCount;
    const involvedRobotsTableValid = selectedRobotsCount > 0;
    _base.toggleSuccessDangerState(involvedRobotsSectionTitle, involvedRobotsTableValid, true);
    _base.toggleSuccessDangerState(involvedRobotsSectionTitleIcon, involvedRobotsTableValid, true);
    _base.toggleSuccessDangerState(involvedRobotsCount, involvedRobotsTableValid);

    let selectedQueuesCount = 0
    if ($.fn.dataTable.isDataTable(`${formSelector} ${base.selectors.involvedQueuesTable}`)) {
        selectedQueuesCount = $(`${formSelector} ${base.selectors.involvedQueuesTable}`).DataTable().rows({ selected: true }).count();
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

    if (currentMode === 'add') {
        form.querySelector(base.selectors.createButton).disabled = !formValid;
    } else {
        form.querySelector(base.selectors.saveButton).disabled = !formValid;
    }
};

const loadProcesses = async (e = null, id_ = null) => {
    const form = (currentMode === 'add' ? base.elements.addForm : document.querySelector(base.selectors.editForm));
    const formSelector = (currentMode === 'add' ? base.selectors.addForm : base.selectors.editForm);

    try {
        _base.renderLoader(form.querySelector(base.selectors.involvedProcessesSection));
        const id = (e ? e.target.value.trim() : id_);
        
        const tableSelector = `${formSelector} ${base.selectors.involvedProcessesTable}`;
        if (id !== "0") {
            const client = new Client(id);
            client.getProcessesFromOrchestrator().then(response => {
                const data = response.data;
                form.querySelector(base.selectors.involvedProcessesTable).classList.add('table');
                let selected = [];
                if (data.error) {
                    toastr.error(`Unable to load processes: ${data.error}`, null, {
                        positionClass: 'toast-bottom-left'
                    });
                    if ($.fn.dataTable.isDataTable(tableSelector)) {
                        $(tableSelector).DataTable().clear().draw();
                    }
                } else {
                    if ($.fn.dataTable.isDataTable(tableSelector)) {
                        $(tableSelector).DataTable().destroy();
                    }
                    $(tableSelector).DataTable({
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
                        checkForm();
                        refreshInvolvedRobotsTable();
                    });
                    
                    const processesTable = form.querySelector(base.selectors.involvedProcessesTable);
                    
                    if (processesTable.dataset.selected) {
                        const selectedItems = JSON.parse(processesTable.dataset.selected);
                        $(`${base.selectors.editForm} ${base.selectors.involvedProcessesTable}`).DataTable().rows((index, data, node) => {
                            return selectedItems.indexOf(data.Id) >= 0;
                        }).select();
                    }

                    checkForm();
                }
                _base.clearLoader(form.querySelector(base.selectors.involvedProcessesSection));
            });
        } else {
            if ($.fn.dataTable.isDataTable(tableSelector)) {
                $(tableSelector).DataTable().clear().draw();
            }
            _base.clearLoader(form.querySelector(base.selectors.involvedProcessesSection));
        }
    } catch (error) {
        _base.clearLoader(form.querySelector(base.selectors.involvedProcessesSection));
        console.log(error);
    }
};

let involvedRobotsTableData;

const loadRobots = async (e = null, id_) => {
    const form = (currentMode === 'add' ? base.elements.addForm : document.querySelector(base.selectors.editForm));
    const formSelector = (currentMode === 'add' ? base.selectors.addForm : base.selectors.editForm);

    try {
        _base.renderLoader(form.querySelector(base.selectors.involvedRobotsSection));
        const id = (e ? e.target.value.trim() : id_);
        
        const tableSelector = `${formSelector} ${base.selectors.involvedRobotsTable}`;
        if (id !== "0") {
            const client = new Client(id);
            client.getRobotsFromOrchestrator().then(response => {
                const data = response.data;
                form.querySelector(base.selectors.involvedRobotsTable).classList.add('table');
                let selected = [];
                if (data.error) {
                    toastr.error(`Unable to load robots: ${data.error}`, null, {
                        positionClass: 'toast-bottom-left'
                    });
                    if ($.fn.dataTable.isDataTable(tableSelector)) {
                        $(tableSelector).DataTable().clear().draw();
                    }
                } else {
                    if ($.fn.dataTable.isDataTable(tableSelector)) {
                        $(tableSelector).DataTable().destroy();
                    }
                    $(tableSelector).DataTable({
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
                    }).on('select deselect', checkForm);
                    involvedRobotsTableData = data;

                    const robotsTable = form.querySelector(base.selectors.involvedRobotsTable);
                    
                    if (robotsTable.dataset.selected) {
                        const selectedItems = JSON.parse(robotsTable.dataset.selected);
                        $(`${base.selectors.editForm} ${base.selectors.involvedRobotsTable}`).DataTable().rows((index, data, node) => {
                            return selectedItems.indexOf(data.Id) >= 0;
                        }).select();
                    }
                    // trigger processes selection to refresh involved robots table content
                    $(`${base.selectors.editForm} ${base.selectors.involvedProcessesTable}`).trigger('select');

                    checkForm();
                }
                _base.clearLoader(form.querySelector(base.selectors.involvedRobotsSection));
            });
        } else {
            if ($.fn.dataTable.isDataTable(tableSelector)) {
                $(tableSelector).DataTable().clear().draw();
            }
            _base.clearLoader(form.querySelector(base.selectors.involvedRobotsSection));
        }
    } catch (error) {
        _base.clearLoader(form.querySelector(base.selectors.involvedRobotsSection));
        console.log(error);
    }
};

const refreshInvolvedRobotsTable = () => {
    const form = (currentMode === 'add' ? base.elements.addForm : document.querySelector(base.selectors.editForm));
    const formSelector = (currentMode === 'add' ? base.selectors.addForm : base.selectors.editForm);

    try {
        _base.renderLoader(form.querySelector(base.selectors.involvedRobotsTable));

        const processesTableSelector = `${formSelector} ${base.selectors.involvedProcessesTable}`;
        if ($.fn.dataTable.isDataTable(processesTableSelector)) {
            const involvedProcessesRows = $(processesTableSelector).DataTable().rows({ selected: true }).data();
            let involvedProcessesEnvironments = [];
            for (let i = 0; i < involvedProcessesRows.length; i++) {
                const environment = involvedProcessesRows[i]['EnvironmentName'];
                if (involvedProcessesEnvironments.indexOf(environment) < 0) {
                    involvedProcessesEnvironments.push(environment);
                }
            }
            
            const robotsTableSelector = `${formSelector} ${base.selectors.involvedRobotsTable}`;
            if ($.fn.dataTable.isDataTable(robotsTableSelector)) {
                const table = $(robotsTableSelector).DataTable();
                const previouslySelectedRows = table.rows({ selected: true }).data();
                table.clear().rows.add(involvedRobotsTableData);
                if (involvedProcessesRows.length > 0) {
                    table.rows((index, data, node) => {
                        let environments = data['RobotEnvironments'].split(',').map(item => {
                            return item.trim();
                        });
                        let remove = true;
                        for (let i = 0; i < involvedProcessesEnvironments.length; i++) {
                            if (environments.indexOf(involvedProcessesEnvironments[i]) >= 0) {
                                remove = false;
                                break;
                            }
                        }
                        return remove;
                        //return environments.some(item => involvedProcessesEnvironments.indexOf(item) < 0);
                    }).remove();
                }
                table.draw();
                // reselect previously selected rows
                table.rows((index, data, node) => {
                    return previouslySelectedRows.map(item => { return item['Id']; }).indexOf(data['Id']) >= 0;
                }).select();
            }
            checkForm();
        }
        _base.clearLoader(form.querySelector(base.selectors.involvedRobotsTable));
    } catch (error) {
        _base.clearLoader(form.querySelector(base.selectors.involvedRobotsTable));
        console.log(error);
    }
};

const loadQueues = async (e = null, id_) => {
    const form = (currentMode === 'add' ? base.elements.addForm : document.querySelector(base.selectors.editForm));
    const formSelector = (currentMode === 'add' ? base.selectors.addForm : base.selectors.editForm);

    try {
        _base.renderLoader(form.querySelector(base.selectors.involvedQueuesSection));
        const id = (e ? e.target.value.trim() : id_);
        
        const tableSelector = `${formSelector} ${base.selectors.involvedQueuesTable}`;
        if (id !== "0") {
            const client = new Client(id);
            client.getQueuesFromOrchestrator().then(response => {
                const data = response.data;
                form.querySelector(base.selectors.involvedQueuesTable).classList.add('table');
                let selected = [];
                if (data.error) {
                    toastr.error(`Unable to load queues: ${data.error}`, null, {
                        positionClass: 'toast-bottom-left'
                    });
                    if ($.fn.dataTable.isDataTable(tableSelector)) {
                        $(tableSelector).DataTable().clear().draw();
                    }
                } else {
                    if ($.fn.dataTable.isDataTable(tableSelector)) {
                        $(tableSelector).DataTable().destroy();
                    }
                    $(tableSelector).DataTable({
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
                    }).on('select deselect', checkForm);

                    const queuesTable = form.querySelector(base.selectors.involvedQueuesTable);
                    
                    if (queuesTable.dataset.selected) {
                        const selectedItems = JSON.parse(queuesTable.dataset.selected);
                        $(`${base.selectors.editForm} ${base.selectors.involvedQueuesTable}`).DataTable().rows((index, data, node) => {
                            return selectedItems.indexOf(data.Id) >= 0;
                        }).select();
                    }

                    checkForm();
                }
                _base.clearLoader(form.querySelector(base.selectors.involvedQueuesSection));
            });
        } else {
            if ($.fn.dataTable.isDataTable(tableSelector)) {
                $(tableSelector).DataTable().clear().draw();
            }
            _base.clearLoader(form.querySelector(base.selectors.involvedQueuesSection));
        }
    } catch (error) {
        _base.clearLoader(form.querySelector(base.selectors.involvedQueuesSection));
        console.log(error);
    }
};

const create = async () => {
    const form = base.elements.addForm;
    const formSelector = (currentMode === 'add' ? base.selectors.addForm : base.selectors.editForm);
    try {
        _base.renderLoader(form);

        const clientSelect = form.querySelector(base.selectors.clientSelect);
        const nameInput = form.querySelector(base.selectors.nameInput);
        const codeInput = form.querySelector(base.selectors.codeInput);
        const operationalHandbookPageURLInput = form.querySelector(base.selectors.operationalHandbookPageURLInput);
        const kibanaDashboardURLInput = form.querySelector(base.selectors.kibanaDashboardURLInput);
        const additionalInformationTextarea = form.querySelector(base.selectors.additionalInformationTextarea);
        const runningPeriodSectionTitle = form.querySelector(base.selectors.runningPeriodSectionTitle);
        const runningPeriodSectionTitleIcon = form.querySelector(base.selectors.runningPeriodSectionTitleIcon);
        const runningPeriodMondayCheckbox = form.querySelector(base.selectors.runningPeriodMondayCheckbox);
        const runningPeriodTuesdayCheckbox = form.querySelector(base.selectors.runningPeriodTuesdayCheckbox);
        const runningPeriodWednesdayCheckbox = form.querySelector(base.selectors.runningPeriodWednesdayCheckbox);
        const runningPeriodThursdayCheckbox = form.querySelector(base.selectors.runningPeriodThursdayCheckbox);
        const runningPeriodFridayCheckbox = form.querySelector(base.selectors.runningPeriodFridayCheckbox);
        const runningPeriodSaturdayCheckbox = form.querySelector(base.selectors.runningPeriodSaturdayCheckbox);
        const runningPeriodSundayCheckbox = form.querySelector(base.selectors.runningPeriodSundayCheckbox);

        const involvedProcessesRows = $(`${formSelector} ${base.selectors.involvedProcessesTable}`).DataTable().rows({ selected: true }).data();
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

        const involvedRobotsRows = $(`${formSelector} ${base.selectors.involvedRobotsTable}`).DataTable().rows({ selected: true }).data();
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

        const involvedQueuesRows = $(`${formSelector} ${base.selectors.involvedQueuesTable}`).DataTable().rows({ selected: true }).data();
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
            const calendar = form.querySelector(base.selectors.runningPeriodCalendar).bulmaCalendar;
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
                    _base.clearLoader(form);
                })
            )
        });
    } catch (error) {
        toastr.error(`Watched automated process not added due to application exception: ${error}`, null, {
            positionClass: 'toast-bottom-left'
        });
        console.log(error);
        _base.clearLoader(form);
    }
};

const update = async () => {
    const form = document.querySelector(base.selectors.editForm);
    const formSelector = (currentMode === 'add' ? base.selectors.addForm : base.selectors.editForm);
    try {
        _base.renderLoader(form);

        const clientSelect = form.querySelector(base.selectors.clientSelect);
        const nameInput = form.querySelector(base.selectors.nameInput);
        const codeInput = form.querySelector(base.selectors.codeInput);
        const operationalHandbookPageURLInput = form.querySelector(base.selectors.operationalHandbookPageURLInput);
        const kibanaDashboardURLInput = form.querySelector(base.selectors.kibanaDashboardURLInput);
        const additionalInformationTextarea = form.querySelector(base.selectors.additionalInformationTextarea);
        const runningPeriodSectionTitle = form.querySelector(base.selectors.runningPeriodSectionTitle);
        const runningPeriodSectionTitleIcon = form.querySelector(base.selectors.runningPeriodSectionTitleIcon);
        const runningPeriodMondayCheckbox = form.querySelector(base.selectors.runningPeriodMondayCheckbox);
        const runningPeriodTuesdayCheckbox = form.querySelector(base.selectors.runningPeriodTuesdayCheckbox);
        const runningPeriodWednesdayCheckbox = form.querySelector(base.selectors.runningPeriodWednesdayCheckbox);
        const runningPeriodThursdayCheckbox = form.querySelector(base.selectors.runningPeriodThursdayCheckbox);
        const runningPeriodFridayCheckbox = form.querySelector(base.selectors.runningPeriodFridayCheckbox);
        const runningPeriodSaturdayCheckbox = form.querySelector(base.selectors.runningPeriodSaturdayCheckbox);
        const runningPeriodSundayCheckbox = form.querySelector(base.selectors.runningPeriodSundayCheckbox);

        const involvedProcessesRows = $(`${formSelector} ${base.selectors.involvedProcessesTable}`).DataTable().rows({ selected: true }).data();
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

        const involvedRobotsRows = $(`${formSelector} ${base.selectors.involvedRobotsTable}`).DataTable().rows({ selected: true }).data();
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

        const involvedQueuesRows = $(`${formSelector} ${base.selectors.involvedQueuesTable}`).DataTable().rows({ selected: true }).data();
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
            console.log(form.dataset.id);
            const watchedAutomatedProcess = new WatchedAutomatedProcess(form.dataset.id);
            const calendar = form.querySelector(base.selectors.runningPeriodCalendar).bulmaCalendar;
            resolve(
                watchedAutomatedProcess.update(
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
                    _base.clearLoader(form);
                })
            )
        });
    } catch (error) {
        toastr.error(`Watched automated process not updated due to application exception: ${error}`, null, {
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
            const watchedAutomatedProcess = new WatchedAutomatedProcess(form.dataset.id);
            resolve(
                watchedAutomatedProcess.remove().then(response => {
                    resetForm();
                    _base.clearLoader(form);
                })
            )
        });
    } catch (error) {
        toastr.error(`Watched automated process not removed due to application exception: ${error}`, null, {
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
                configuration.updateWatchedAutomatedProcessesTable().then(res => {
                    view.updateTable(configuration.watchedAutomatedProcessesTable);
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
        const formSelector = (currentMode === 'add' ? base.selectors.addForm : base.selectors.editForm);

        const clientSelect = form.querySelector(base.selectors.clientSelect);
        const nameInput = form.querySelector(base.selectors.nameInput);
        const codeInput = form.querySelector(base.selectors.codeInput);
        const operationalHandbookPageURLInput = form.querySelector(base.selectors.operationalHandbookPageURLInput);
        const kibanaDashboardURLInput = form.querySelector(base.selectors.kibanaDashboardURLInput);
        const additionalInformationTextarea = form.querySelector(base.selectors.additionalInformationTextarea);
        const runningPeriodSectionTitle = form.querySelector(base.selectors.runningPeriodSectionTitle);
        const runningPeriodSectionTitleIcon = form.querySelector(base.selectors.runningPeriodSectionTitleIcon);
        const runningPeriodMondayCheckbox = form.querySelector(base.selectors.runningPeriodMondayCheckbox);
        const runningPeriodTuesdayCheckbox = form.querySelector(base.selectors.runningPeriodTuesdayCheckbox);
        const runningPeriodWednesdayCheckbox = form.querySelector(base.selectors.runningPeriodWednesdayCheckbox);
        const runningPeriodThursdayCheckbox = form.querySelector(base.selectors.runningPeriodThursdayCheckbox);
        const runningPeriodFridayCheckbox = form.querySelector(base.selectors.runningPeriodFridayCheckbox);
        const runningPeriodSaturdayCheckbox = form.querySelector(base.selectors.runningPeriodSaturdayCheckbox);
        const runningPeriodSundayCheckbox = form.querySelector(base.selectors.runningPeriodSundayCheckbox);
        const involvedProcessesSectionTitle = form.querySelector(base.selectors.involvedProcessesSectionTitle);
        const involvedProcessesSectionTitleIcon = form.querySelector(base.selectors.involvedProcessesSectionTitleIcon);
        const involvedProcessesCount = form.querySelector(base.selectors.involvedProcessesCount);
        const involvedRobotsSectionTitle = form.querySelector(base.selectors.involvedRobotsSectionTitle);
        const involvedRobotsSectionTitleIcon = form.querySelector(base.selectors.involvedRobotsSectionTitleIcon);
        const involvedRobotsCount = form.querySelector(base.selectors.involvedRobotsCount);
        const involvedQueuesSectionTitle = form.querySelector(base.selectors.involvedQueuesSectionTitle);
        const involvedQueuesSectionTitleIcon = form.querySelector(base.selectors.involvedQueuesSectionTitleIcon);
        const involvedQueuesCount = form.querySelector(base.selectors.involvedQueuesCount);

        form.querySelector(base.selectors.runningPeriodCalendar).bulmaCalendar.clear();
        if ($.fn.dataTable.isDataTable(`${formSelector} ${base.selectors.involvedProcessesTable}`)) {
            $(`${formSelector} ${base.selectors.involvedProcessesTable}`).DataTable().clear().draw();
        }
        if ($.fn.dataTable.isDataTable(`${formSelector} ${base.selectors.involvedRobotsTable}`)) {
            $(`${formSelector} ${base.selectors.involvedRobotsTable}`).DataTable().clear().draw();
        }
        if ($.fn.dataTable.isDataTable(`${formSelector} ${base.selectors.involvedQueuesTable}`)) {
            $(`${formSelector} ${base.selectors.involvedQueuesTable}`).DataTable().clear().draw();
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

        const calendar = form.querySelector(base.selectors.runningPeriodCalendar);
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