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
            positionClass: 'toast-bottom-center'
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
            positionClass: 'toast-bottom-center'
        });
        view.clearLoaders(id);
    }
};

export const close = async(dashboard, id) => {
    try {
        const alert = new Alert(id);
        view.renderLoaders(id);
        alert.update().then(res => {
            const modal = view.showClosingFormModal(alert.data);
            modal.addEventListener('click', async (e) => {
                if (e.target.matches(`${_base.selectors.validateModalButton}, ${_base.selectors.validateModalButtonChildren}`)) {
                    commitClose(dashboard, alert);
                }
            });
            view.clearLoaders(id);
        });
    } catch (error) {
        toastr.error(`Alert not updated due to application exception: ${error}`, null, {
            positionClass: 'toast-bottom-center'
        });
        view.clearLoaders(id);
    }
};

export const undoClose = async() => {
    try {
        view.removeClosingFormModal();
    } catch (error) {
        console.log(error);
    }
};

export const commitClose = async(dashboard, alert) => {
    try {
        const descriptionTextarea = document.querySelector(base.selectors.closingDescriptionTextarea);
        const falsePositiveCheckbox = document.querySelector(base.selectors.closingFalsePositiveCheckbox);

        if (descriptionTextarea.value.trim() === '') {
            toastr.error('Description is mandatory!', null, {
                positionClass: 'toast-bottom-center'
            });
            descriptionTextarea.classList.add('is-danger');
        } else {
            view.removeClosingFormModal();
            view.renderLoaders(alert.id);
            try {
                alert.close(falsePositiveCheckbox.checked, descriptionTextarea.value.trim()).then(res => {
                    /*updateAfterAction(dashboard, 'closing_related_action', alert)*/
                    dashboardController.update().then(res => {
                        view.clearLoaders(id);
                    });
                });
            } catch (error) {
                toastr.error(`Alert not closed due to application exception: ${error}`, null, {
                    positionClass: 'toast-bottom-center'
                });
                view.clearLoaders(alert.id);
            }
        }
    } catch (error) {
        toastr.error(`Alert closing not committed due to application exception: ${error}`, null, {
            positionClass: 'toast-bottom-center'
        });
    }
};

export const ignore = async(dashboard, id) => {
    try {
        const alert = new Alert(id);
        view.renderLoaders(id);
        alert.update().then(res => {
            const modal = view.showIgnoranceFormModal(alert.data);
            modal.addEventListener('click', async (e) => {
                if (e.target.matches(`${_base.selectors.validateModalButton}, ${_base.selectors.validateModalButtonChildren}`)) {
                    commitIgnore(dashboard, alert);
                }
            });
            view.clearLoaders(id);
        });
    } catch (error) {
        toastr.error(`Alert not updated due to application exception: ${error}`, null, {
            positionClass: 'toast-bottom-center'
        });
        view.clearLoaders(id);
    }
};

export const undoIgnore = async(id) => {
    try {
        view.removeIgnoranceFormModal();
    } catch (error) {
        console.log(error);
    }
};

export const commitIgnore = async(dashboard, alert) => {
    try {
        const ignoranceCalendar = document.querySelector(base.selectors.ignoranceCalendar).bulmaCalendar;
        const descriptionTextarea = document.querySelector(base.selectors.ignoranceDescriptionTextarea);

        if (ignoranceCalendar.startDate === undefined || ignoranceCalendar.startTime === undefined) {
            toastr.error('Start date & time are mandatory!', null, {
                positionClass: 'toast-bottom-center'
            });
        } else {
            if (descriptionTextarea.value.trim() === '') {
                toastr.error('Description is mandatory!', null, {
                    positionClass: 'toast-bottom-center'
                });
                descriptionTextarea.classList.add('is-danger');
            } else {
                if (ignoranceCalendar.endDate === undefined) {
                    // ask user to validate infinite ignorance (manual action to reactivate alert triggering)
                    _base.swalWithBulmaButtons.fire({
                        title: 'Infinite ignorance confirmation',
                        text: 'The alert will be ignored forever, manual action to reactivate it will be needed!',
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonText: 'Ignore it!',
                        cancelButtonText: 'Undo'
                    }).then(result => {
                        if (result.value) {
                            handleCommitIgnore(dashboard, alert, ignoranceCalendar, descriptionTextarea);
                        }
                    });
                } else {
                    handleCommitIgnore(dashboard, alert, ignoranceCalendar, descriptionTextarea);
                }
            }
        }
    } catch (error) {
        toastr.error(`Alert ignorance not committed due to application exception: ${error}`, null, {
            positionClass: 'toast-bottom-center'
        });
    }
};

const handleCommitIgnore = async(dashboard, alert, ignoranceCalendar, descriptionTextarea) => {
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
            descriptionTextarea.value.trim()
        ).then(res => {
            /*updateAfterAction(dashboard, 'closing_related_action', alert)*/
            dashboardController.update().then(res => {
                view.clearLoaders(alert.id);
            });
        });
    } catch (error) {
        toastr.error(`Alert not ignored due to application exception: ${error}`, null, {
            positionClass: 'toast-bottom-center'
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