import * as base from './base';
import * as shutdownAlertTriggersFormModal from './forms/shutdown-alert-triggers-form-modal';
import * as reactivateAlertTriggersFormModal from './forms/reactivate-alert-triggers-form-modal';

export const showShutdownAlertTriggersFormModal = () => {
    const markup = shutdownAlertTriggersFormModal.markup();
    document.body.insertAdjacentHTML('beforeend', markup);
    let modal = document.getElementById(base.strings.shutdownAlertTriggersFormModalID);
    base.showModal(modal);
    return modal;
};

export const removeShutdownAlertTriggersFormModal = () => {
    const modal = document.getElementById(base.strings.shutdownAlertTriggersFormModalID);
    base.closeModal(modal);
};

export const showReactivateAlertTriggersFormModal = () => {
    const markup = reactivateAlertTriggersFormModal.markup();
    document.body.insertAdjacentHTML('beforeend', markup);
    let modal = document.getElementById(base.strings.reactivateAlertTriggersFormModalID);
    base.showModal(modal);
    return modal;
};

export const removeReactivateAlertTriggersFormModal = () => {
    const modal = document.getElementById(base.strings.reactivateAlertTriggersFormModalID);
    base.closeModal(modal);
};