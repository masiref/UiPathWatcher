import bulmaCalendar from 'bulma-calendar';
import moment from 'moment';
import { strings, elements, selectors } from './base';
import * as closingFormModal from './forms/closing-form-modal';
import * as ignoranceFormModal from './forms/ignorance-form-modal';
import * as _base from '../base';

const renderLoaderOnBox = (id) => {
    _base.renderLoader(elements.box(id));
};

const renderLoaderOnTables = () => {
    _base.renderLoader(elements.pendingTable);
    _base.renderLoader(elements.closedTable);
};

export const renderLoaders = (id) => {
    renderLoaderOnBox(id);
    renderLoaderOnTables();
};

const clearLoaderOnBox = (id) => {
    _base.clearLoader(elements.box(id));
};

const clearLoaderOnTable = () => {
    _base.clearLoader(elements.pendingTable);
    _base.clearLoader(elements.closedTable);
};

export const clearLoaders = (id) => {
    clearLoaderOnBox(id);
    clearLoaderOnTable();
};

export const update = (id, markup) => {
    return _base.update(elements.box(id), markup);
};

const removeBox = (id) => {
    const box = elements.box(id);
    if (box)
        box.parentNode.removeChild(box);
};

const removeRow = (id) => {
    const row = elements.row(id);
    if (row)
        row.parentNode.removeChild(row);
};

export const remove = (id) => {
    removeBox(id);
    removeRow(id);
};

export const updatePendingTable = markup => {
    return _base.update(elements.pendingTable.closest(selectors.tableDataTablesWrapper), markup);
};

export const updateClosedTable = markup => {
    return _base.update(elements.closedTable.closest(selectors.tableDataTablesWrapper), markup);
};

export const updateRow = (id, markup) => {
    return _base.update(elements.row(id), markup);
};

export const showClosingFormModal = alert => {
    const markup = closingFormModal.markup(strings.closingFormModalID, alert);
    document.body.insertAdjacentHTML('beforeend', markup);
    let modal = document.getElementById(strings.closingFormModalID);
    _base.showModal(modal);
    return modal;
};

export const removeClosingFormModal = () => {
    const modal = document.getElementById(strings.closingFormModalID);
    _base.closeModal(modal);
};

export const showIgnoranceFormModal = alert => {
    const markup = ignoranceFormModal.markup(strings.ignoranceFormModalID, alert);
    document.body.insertAdjacentHTML('beforeend', markup);
    const now = moment();
    bulmaCalendar.attach(selectors.ignoranceFormModalIgnoreUntilDatetimeInput, {
        type: 'datetime',
        lang: 'en',
        isRange: true,
        headerPosition: 'bottom',
        labelFrom: 'From',
        labelTo: 'To',
        dateFormat: 'DD/MM/YYYY',
        timeFormat: 'HH:mm',
        displayMode: 'inline',
        weekStart: 1,
        startDate: now,
        startTime: now.format('HH:mm'),
        minDate: now,
        showFooter: false
    });
    //const ignoreUntilDatetime = document.getElementById(strings.ignoranceFormModalIgnoreUntilDatetimeInputID);
    let modal = document.getElementById(strings.ignoranceFormModalID);
    _base.showModal(modal);
    return modal;
};

export const removeIgnoranceFormModal = () => {
    const modal = document.getElementById(strings.ignoranceFormModalID);
    _base.closeModal(modal);
};