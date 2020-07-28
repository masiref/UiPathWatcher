import Swal from 'sweetalert2';
const luceneParser = require('lucene-query-parser');

export const strings = {
    shutdownAlertTriggersFormModalID: 'shutdown-alert-triggers-modal',
    reactivateAlertTriggersFormModalID: 'reactivate-alert-triggers-modal'
};

export const selectors = {
    app: '#app',
    shutdownAlertTriggersButton: 'button#shutdown-alert-triggers',
    shutdownAlertTriggersButtonChildren: 'button#shutdown-alert-triggers *',
    shutdownAlertTriggersReasonTextarea: 'form.shutdown-alert-triggers-form textarea#reason',
    reactivateAlertTriggersButton: 'button#reactivate-alert-triggers',
    reactivateAlertTriggersButtonChildren: 'button#reactivate-alert-triggers *',
    reactivateAlertTriggersReasonTextarea: 'form.reactivate-alert-triggers-form textarea#reason',
    closeModalTriggers: '.modal button.delete, .modal button.cancel, .modal button.cancel *',
    validateModalButton: '.modal button.validate',
    validateModalButtonChildren: '.modal button.validate *',
    tableDataTablesWrapper: '.dataTables_wrapper',
    dateTimeCalendarWrapper: '.datetimepicker-dummy',
    dateTimeCalendarFromInput: 'input.datetimepicker-dummy-input[placeholder="From"]',
    dateTimeCalendarToInput: 'input.datetimepicker-dummy-input[placeholder="To"]',
    dateTimeFooterCancelButton: 'button.datetimepicker-footer-cancel',
    formControlWrapper: '.control'
};

export const elements = {
    app: document.querySelector(selectors.app)
};

export const update = (old, markup) => {
    if (old) {
        var node = htmlToElement(markup);
        old.parentNode.replaceChild(node, old);
        return node;
    }
};

export const htmlToElement = html => {
    var template = document.createElement('template');
    template.innerHTML = html;
    return template.content.firstChild;
};

export const htmlToElements = html => {
    var template = document.createElement('template');
    template.innerHTML = html;
    return template.content.childNodes;
};

export const renderLoader = parent => {
    if (parent)
        parent.classList.add('is-loading');
};

export const clearLoader = parent => {
    if (parent)
        parent.classList.remove('is-loading');
};

export const animateCSS = (node, animationName, callback) => {
    node.classList.add('animated', animationName)
    function handleAnimationEnd() {
        node.classList.remove('animated', animationName)
        node.removeEventListener('animationend', handleAnimationEnd)

        if (typeof callback === 'function') callback()
    }
    node.addEventListener('animationend', handleAnimationEnd)
};

export const showModal = modal => {
    modal.classList.add('is-active');
    animateCSS(modal, 'slideInUp');
    modal.addEventListener('click', e => {
        if (e.target.matches(selectors.closeModalTriggers)) {
            closeModal(modal);
        }
    });
};

export const closeModal = modal => {
    animateCSS(modal, 'slideOutDown', () => {
        modal.classList.remove('is-active');
        modal.parentNode.removeChild(modal);
    });
};

export const swalWithBulmaButtons = Swal.mixin({
    customClass: {
        confirmButton: 'button is-success',
        cancelButton: 'button is-danger',
        actions: 'buttons'
    },
    buttonsStyling: false,
    focusConfirm: false,
    scrollbarPadding: false
});

export const isDashboardRelatedURL = url => {
    let isRelated = /.*\/dashboard\/.*|.*\/$/;
    return isRelated.test(url);
};

export const getClientIDFromURL = url => {
    let isRelated = /.*\/client\/(\d)$/;
    if (isRelated.test(url)) {
        return url.match(isRelated)[1];
    }
    return null;
};

export const isUserRelatedURL = url => {
    let isRelated = /.*\/user$/;
    return isRelated.test(url);
};

export const isConfigurationOrchestratorRelatedURL = url => {
    let isRelated = /.*\/configuration\/orchestrator$/;
    return isRelated.test(url);
};

export const isConfigurationClientRelatedURL = url => {
    let isRelated = /.*\/configuration\/client$/;
    return isRelated.test(url);
};

export const isConfigurationWatchedAutomatedProcessRelatedURL = url => {
    let isRelated = /.*\/configuration\/watched-automated-process$/;
    return isRelated.test(url);
};

export const isConfigurationAlertTriggerRelatedURL = url => {
    let isRelated = /.*\/configuration\/alert-trigger$/;
    return isRelated.test(url);
};

export const validURL = str => {
    const pattern = new RegExp('^(https?:\\/\\/)'); // protocol
        /*
        '((([a-z\\d]([a-z\\d-]*[a-z\\d])*)\\.?)+[a-z]{2,}|'+ // domain name
        '((\\d{1,3}\\.){3}\\d{1,3}))'+ // OR ip (v4) address
        '(\\:\\d+)?(\\/[-a-z\\d%_.~+]*)*'+ // port and path
        '(\\?[;&a-z\\d%_.~+=-]*)?'+ // query string
        '(<\\#[-a-z\\d_]*)?$','i'); // fragment locator
        */
    return !!pattern.test(str);
};

export const toggleSuccessDangerState = (element, success, isText = false) => {
    const classNames = [
        isText ? 'has-text-success' : 'is-success',
        isText ? 'has-text-danger' : 'is-danger'
    ];
    if (success) {
        element.classList.remove(classNames[1]);
        element.classList.add(classNames[0]);
    } else {
        element.classList.add(classNames[1]);
        element.classList.remove(classNames[0]);
    }
};

export const toggleFormControlTooltip = (element, success) => {
    const formControlWrapper = element.closest(selectors.formControlWrapper);
    if (success) {
        formControlWrapper.classList.remove('has-tooltip-active');
    } else {
        formControlWrapper.classList.add('has-tooltip-active');
    }
};

export const removeStates = element => {
    element.classList.remove('is-success', 'is-info', 'is-warning', 'is-danger', 'has-text-success', 'has-text-info', 'has-text-warning', 'has-text-danger');
};

export const removeSelectOptions = (element, keepFirst = false) => {
    let last = element.options.length - 1;
    for (let i = last; i >= (keepFirst ? 1 : 0); i--) {
        element.remove(i);
    }
};

export const isNormalInteger = str => {
    var n = Math.floor(Number(str));
    return n !== Infinity && String(n) === str && n >= 0;
};

export const timeStringToFloat = time => {
    var hoursMinutes = time.split(/[.:]/);
    var hours = parseInt(hoursMinutes[0], 10);
    var minutes = hoursMinutes[1] ? parseInt(hoursMinutes[1], 10) : 0;
    return hours + minutes / 60;
};

export const isValidLuceneString = str => {
    let valid = false;

    try {
        luceneParser.parse(str);
        valid = true;
    } catch (error) {}

    return valid;
};