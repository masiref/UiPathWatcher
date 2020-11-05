import bulmaCalendar from 'bulma-calendar';

import toastr from 'toastr';

import Alert from '../../models/Alert';

import * as _base from '../base';
import * as base from './base';
import * as view from './view';

import * as dashboardController from '../dashboard/index';
import * as layoutController from '../layout/index';

export const init = async (dashboard, target) => {
    try {
        // Handle timeline button
        if (target.matches(`${base.selectors.timelineButton}, ${base.selectors.timelineButtonChildren}`)) {
            const id = target.closest(base.selectors.timelineButton).dataset.id;
            timeline(id);
        }

        // Handle start revision button
        if (target.matches(`${base.selectors.revisionButton}, ${base.selectors.revisionButtonChildren}`)) {
            const id = target.closest(base.selectors.revisionButton).dataset.id;
            startRevision(dashboard, id);
        }

        // Handle undo revision button
        if (target.matches(`${base.selectors.cancelButton}, ${base.selectors.cancelButtonChildren}`)) {
            const id = target.closest(base.selectors.cancelButton).dataset.id;
            undoRevision(dashboard, id);
        }

        // Handle close button
        if (target.matches(`${base.selectors.closeButton}, ${base.selectors.closeButtonChildren}`)) {
            const id = target.closest(base.selectors.closeButton).dataset.id;
            close(dashboard, id);
        }

        // Handle clean button
        if (target.matches(`${base.selectors.cleanButton}, ${base.selectors.cleanButtonChildren}`)) {
            const id = target.closest(base.selectors.cleanButton).dataset.id;
            clean(dashboard, id);
        }

        // Handle ignore button
        if (target.matches(`${base.selectors.ignoreButton}, ${base.selectors.ignoreButtonChildren}`)) {
            const id = target.closest(base.selectors.ignoreButton).dataset.id;
            ignore(dashboard, id);
        }
    } catch (error) {
        console.log(`Failed to init alert controller: ${error}`);
    }
}

const updateAfterAction = async (dashboard, action, alert) => {
    try {
        if (action === 'revision_related_action') {
            view.update(alert.id, alert.markup);
            let promises = [
                layoutController.updateMenu(dashboard),
                layoutController.updateSidebar(dashboard),
                dashboardController.updateTiles(),
                updatePendingTable(dashboard)
            ];
            if (!dashboard.userRelated) {
                view.updateRow(alert.id, alert.rowMarkup);
            }
            return Promise.all(promises);
        } else {
            if (action === 'closing_related_action') {
                //view.remove(alert.id);
                return dashboardController.update();
            }
        }
    } catch (error) {
        console.log(error);
    }
};

export const timeline = async(id) => {
    try {
        const alert = new Alert(id);
        view.renderLoaders(id);
        alert.timeline().then(res => {
            const modal = view.showTimelineFormModal(alert);
            view.clearLoaders(id);
        });
    } catch (error) {
        toastr.error(`Alert timeline not shown due to application exception: ${error}`, null, {
            positionClass: 'toast-bottom-left'
        });
        view.clearLoaders(id);
    }
};

export const startRevision = async(dashboard, id) => {
    try {
        const alert = new Alert(id);
        view.renderLoaders(id);
        alert.enterRevisionMode().then(res => {
            /*updateAfterAction(dashboard, 'revision_related_action', alert)*/
            dashboardController.update().then(res => {
                view.clearLoaders(id);
            });
        });
    } catch (error) {
        toastr.error(`Revision not started on alert due to application exception: ${error}`, null, {
            positionClass: 'toast-bottom-left'
        });
        view.clearLoaders(id);
    }
};

export const undoRevision = async(dashboard, id) => {    
    try {
        const alert = new Alert(id);
        view.renderLoaders(id);
        alert.exitRevisionMode().then(res => {
            /*updateAfterAction(dashboard, 'revision_related_action', alert)*/
            dashboardController.update().then(res => {
                view.clearLoaders(alert.id);
            });
        });
    } catch (error) {
        toastr.error(`Revision not cancelled on alert due to application exception: ${error}`, null, {
            positionClass: 'toast-bottom-left'
        });
        view.clearLoaders(id);
    }
};

export const close = async(dashboard, id) => {
    try {
        const alert = new Alert(id);
        view.renderLoaders(id);
        alert.closingFormModal().then(res => {
            const modalResult = view.showClosingFormModal(alert);
            const modal = modalResult.modal;
            const keywordsList = modalResult.keywordsList;
            modal.addEventListener('click', async (e) => {
                if (e.target.matches(`${_base.selectors.validateModalButton}, ${_base.selectors.validateModalButtonChildren}`)) {
                    commitClose(dashboard, alert, keywordsList);
                }
            });
            const uiPathRobotTools = modal.querySelector(base.selectors.uiPathRobotTools);
            uiPathRobotTools.addEventListener('click', async (e) => {
                if (e.target.matches('.button, .button *')) {
                    const button = e.target.closest('.button');
                    const descriptionTextarea = document.querySelector(base.selectors.closingDescriptionTextarea);
                    const modalContent = document.querySelector(`#${base.strings.closingFormModalID} .modal-content`);
                    _base.renderLoader(button);
                    _base.renderLoader(modalContent);
                    const result = await _base.runUipathProcess(button.dataset.uipathProcess, {
                        'id': uiPathRobotTools.dataset.id,
                        'keywords': JSON.stringify(keywordsList.items.map(keyword => { return keyword.text; })),
                        'resolutionDescription': document.querySelector(base.selectors.closingDescriptionTextarea).value.trim(),
                        'createdAt': uiPathRobotTools.dataset.createdAt,
                        'revisionStartedAt': uiPathRobotTools.dataset.revisionStartedAt,
                        'messages': uiPathRobotTools.dataset.messages,
                        'triggerId': uiPathRobotTools.dataset.triggerId,
                        'triggerTitle': uiPathRobotTools.dataset.triggerTitle,
                        'definitionId': uiPathRobotTools.dataset.triggerDefinitionId,
                        'definitionLevel': uiPathRobotTools.dataset.triggerDefinitionLevel,
                        'definitionDescription': uiPathRobotTools.dataset.triggerDefinitionDescription,
                        'watchedAutomatedProcessId': uiPathRobotTools.dataset.watchedAutomatedProcessId,
                        'watchedAutomatedProcessCode': uiPathRobotTools.dataset.watchedAutomatedProcessCode,
                        'watchedAutomatedProcessName': uiPathRobotTools.dataset.watchedAutomatedProcessName,
                        'watchedAutomatedProcessOperationalHandbookPageURL': uiPathRobotTools.dataset.watchedAutomatedProcessOperationalHandbookPageURL,
                        'watchedAutomatedProcessKibanaDashboardURL': uiPathRobotTools.dataset.watchedAutomatedProcessKibanaDashboardURL,
                        'watchedAutomatedProcessAdditionalInformation': uiPathRobotTools.dataset.watchedAutomatedProcessAdditionalInformation,
                        'customerId': uiPathRobotTools.dataset.clientId,
                        'customerName': uiPathRobotTools.dataset.clientName,
                        'customerCode': uiPathRobotTools.dataset.clientCode,
                    });
                    if (result.output) {
                        let description = descriptionTextarea.value.trim();
                        description += `\n\n### Execution of ${button.dataset.uipathProcessLabel} on ${(new Date()).toLocaleString('en')}`
                        for (const [key, value] of Object.entries(result.output)) {
                            description += `\n${key}: ${value}`
                        }
                        description += `\n#####`;
                        descriptionTextarea.value = description;
                    }
                    _base.clearLoader(button);
                    _base.clearLoader(modalContent);
                }
            });
            view.clearLoaders(id);
        });
    } catch (error) {
        toastr.error(`Alert closing form not shown due to application exception: ${error}`, null, {
            positionClass: 'toast-bottom-left'
        });
        view.clearLoaders(id);
    }
};

export const commitClose = async(dashboard, alert, keywordsList) => {
    try {
        const descriptionTextarea = document.querySelector(base.selectors.closingDescriptionTextarea);
        const falsePositiveCheckbox = document.querySelector(base.selectors.closingFalsePositiveCheckbox);

        let valid = false;
        if (keywordsList.items.length === 0) {
            toastr.error('At least one keyword must be added!', null, {
                positionClass: 'toast-bottom-left'
            });
            keywordsList.input.parentNode.classList.add('is-danger');
        } else {
            keywordsList.input.parentNode.classList.remove('is-danger');
            valid = true;
        }
        if (descriptionTextarea.value.trim() === '') {
            toastr.error('Description is mandatory!', null, {
                positionClass: 'toast-bottom-left'
            });
            descriptionTextarea.classList.add('is-danger');
            valid = false;
        } else {
            descriptionTextarea.classList.remove('is-danger');
            valid = true;
        }

        if (valid) {
            view.removeClosingFormModal();
            view.renderLoaders(alert.id);
            try {
                alert.close(falsePositiveCheckbox.checked, descriptionTextarea.value.trim(), keywordsList.items).then(res => {
                    /*updateAfterAction(dashboard, 'closing_related_action', alert)*/
                    dashboardController.update().then(res => {
                        view.clearLoaders(alert.id);
                    });
                });
            } catch (error) {
                toastr.error(`Alert not closed due to application exception: ${error}`, null, {
                    positionClass: 'toast-bottom-left'
                });
                view.clearLoaders(alert.id);
            }
        }
    } catch (error) {
        console.log(error);
        toastr.error(`Alert closing not committed due to application exception: ${error}`, null, {
            positionClass: 'toast-bottom-left'
        });
    }
};

export const clean = async(dashboard, id) => {    
    try {
        const alert = new Alert(id);
        view.renderLoaders(id);
        alert.clean().then(res => {
            dashboardController.update().then(res => {
                view.clearLoaders(alert.id);
            });
        });
    } catch (error) {
        toastr.error(`Alert not cleaned due to application exception: ${error}`, null, {
            positionClass: 'toast-bottom-left'
        });
        view.clearLoaders(id);
    }
};

export const ignore = async(dashboard, id) => {
    try {
        const alert = new Alert(id);
        view.renderLoaders(id);
        alert.ignoranceFormModal().then(res => {
            const modalResult = view.showIgnoranceFormModal(alert);
            const modal = modalResult.modal;
            const keywordsList = modalResult.keywordsList;
            modal.addEventListener('click', async (e) => {
                if (e.target.matches(`${_base.selectors.validateModalButton}, ${_base.selectors.validateModalButtonChildren}`)) {
                    commitIgnore(dashboard, alert, keywordsList);
                }
            });
            view.clearLoaders(id);
        });
    } catch (error) {
        toastr.error(`Alert trigger ignorance form not shown due to application exception: ${error}`, null, {
            positionClass: 'toast-bottom-left'
        });
        view.clearLoaders(id);
    }
};

export const commitIgnore = async(dashboard, alert, keywordsList) => {
    try {
        const ignoranceCalendar = document.querySelector(base.selectors.ignoranceCalendar).bulmaCalendar;
        const descriptionTextarea = document.querySelector(base.selectors.ignoranceDescriptionTextarea);
        //const keywordsList = document.querySelector(`#${base.strings.ignoranceFormModalID} ${base.selectors.ignoranceKeywordsList}`).BulmaTagsInput();

        let valid = false;
        if (keywordsList.items.length === 0) {
            toastr.error('At least one keyword must be added!', null, {
                positionClass: 'toast-bottom-left'
            });
            keywordsList.input.parentNode.classList.add('is-danger');
        } else {
            keywordsList.input.parentNode.classList.remove('is-danger');
            valid = true;
        }

        if (ignoranceCalendar.startDate === undefined || ignoranceCalendar.startTime === undefined) {
            toastr.error('Start date & time are mandatory!', null, {
                positionClass: 'toast-bottom-left'
            });
            valid = false;
        } else {
            valid = true;
        }
        if (descriptionTextarea.value.trim() === '') {
            toastr.error('Description is mandatory!', null, {
                positionClass: 'toast-bottom-left'
            });
            descriptionTextarea.classList.add('is-danger');
            valid = false;
        } else {
            descriptionTextarea.classList.remove('is-danger');
            valid = true;
        }
        if (valid) {
            if (ignoranceCalendar.endDate === undefined) {
                // ask user to validate infinite ignorance (manual action to reactivate alert triggering)
                _base.swalWithBulmaButtons.fire({
                    title: 'Infinite ignorance confirmation',
                    text: 'The alert will be ignored forever, manual action to reactivate it will be needed!',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: '<span class="icon"><i class="fas fa-eye-slash"></i></span><span>Ignore it!</span>',
                    cancelButtonText: '<span class="icon"><i class="fas fa-undo"></i></span><span>Undo</span>'
                }).then(result => {
                    if (result.value) {
                        handleCommitIgnore(dashboard, alert, ignoranceCalendar, descriptionTextarea, keywordsList.items);
                    }
                });
            } else {
                handleCommitIgnore(dashboard, alert, ignoranceCalendar, descriptionTextarea, keywordsList.items);
            }
        }
    } catch (error) {
        toastr.error(`Alert ignorance not committed due to application exception: ${error}`, null, {
            positionClass: 'toast-bottom-left'
        });
    }
};

const handleCommitIgnore = async(dashboard, alert, ignoranceCalendar, descriptionTextarea, categories) => {
    try {
        view.removeIgnoranceFormModal();
        view.renderLoaders(alert.id);
        const startDate = ignoranceCalendar.startDate.toISOString().split('T')[0];
        const startTime = ignoranceCalendar.startTime.toTimeString().split(' ')[0];
        let endDate = ignoranceCalendar.endDate;
        if (endDate) {
            endDate = endDate.toISOString().split('T')[0];
        }
        let endTime = ignoranceCalendar.endTime;
        if (endTime) {
            endTime = endTime.toTimeString().split(' ')[0];
        }
        alert.ignore(
            startDate,
            startTime,
            endDate,
            endTime,
            descriptionTextarea.value.trim(),
            categories
        ).then(res => {
            /*updateAfterAction(dashboard, 'closing_related_action', alert)*/
            dashboardController.update().then(res => {
                view.clearLoaders(alert.id);
            });
        });
    } catch (error) {
        toastr.error(`Alert not ignored due to application exception: ${error}`, null, {
            positionClass: 'toast-bottom-left'
        });
        view.clearLoaders(alert.id);
    }
};

export const updatePendingTable = async (dashboard) => {
    try {
        let table = document.querySelector(base.selectors.pendingTable);
        if (table) {
            _base.renderLoader(table);
            return dashboard.updateAlertsTable(false, base.strings.pendingTableID).then(res => {
                view.updatePendingTable(dashboard.pendingAlertsTable);
            }).then(() => {
                _base.clearLoader(table);
            });
        }
    } catch (error) {
        console.log(error);
    }
};

export const updateClosedTable = async (dashboard) => {
    try {
        let table = document.querySelector(base.selectors.closedTable);
        if (table) {
            _base.renderLoader(table);
            return dashboard.updateAlertsTable(true, base.strings.closedTableID).then(res => {
                view.updateClosedTable(dashboard.closedAlertsTable);
            }).then(() => {
                _base.clearLoader(table);
            });
        }
    } catch (error) {
        console.log(error);
    }
};