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
    _base.renderLoader(document.querySelector(selectors.pendingTable));
    _base.renderLoader(document.querySelector(selectors.closedTable));
};

export const renderLoaders = (id) => {
    renderLoaderOnBox(id);
    renderLoaderOnTables();
};

const clearLoaderOnBox = (id) => {
    _base.clearLoader(elements.box(id));
};

const clearLoaderOnTables = () => {
    _base.clearLoader(document.querySelector(selectors.pendingTable));
    _base.clearLoader(document.querySelector(selectors.closedTable));
};

export const clearLoaders = (id) => {
    clearLoaderOnBox(id);
    clearLoaderOnTables();
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
    const table = _base.update(
        document.querySelector(selectors.pendingTable).closest(_base.selectors.tableDataTablesWrapper),
        markup
    );
    $(selectors.pendingTable).DataTable();
    return table;
};

export const updateClosedTable = markup => {
    const table = _base.update(
        document.querySelector(selectors.closedTable).closest(_base.selectors.tableDataTablesWrapper),
        markup
    );
    $(selectors.closedTable).DataTable();
    return table;
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