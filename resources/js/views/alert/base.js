export const strings = {
    box: 'alert-',
    row: 'alert-row-',
    closingFormModalID: 'alert-closing-modal',
    ignoranceFormModalID: 'alert-ignorance-modal',
    timelineFormModalID: 'alert-timeline-modal-',
    pendingTableID: 'pending-alerts-table',
    closedTableID: 'closed-alerts-table'
};

export const selectors = {
    box: '.alert-box',
    boxGeneralInfo: '.alert-box__general-info',
    boxFooter: '.alert-box__footer',
    commentButton: '.comment-btn',
    commentButtonChildren: '.comment-btn *',
    timelineButton: '.timeline-btn',
    timelineButtonChildren: '.timeline-btn *',
    revisionButton: '.revision-btn',
    revisionButtonChildren: '.revision-btn *',
    cancelButton: '.cancel-btn',
    cancelButtonChildren: '.cancel-btn *',
    closeButton: '.close-btn',
    closeButtonChildren: '.close-btn *',
    ignoreButton: '.ignore-btn',
    ignoreButtonChildren: '.ignore-btn *',
    closingFalsePositiveCheckbox: '#closing-false-positive',
    closingKeywordsList: '#closing-keywords',
    closingAddKeywordButton: 'button#closing-add-keyword',
    closingDescriptionTextarea: '#closing-description',
    ignoranceCalendar: '#ignorance-calendar',
    ignoranceKeywordsList: '#ignorance-keywords',
    ignoranceAddKeywordButton: 'button#ignorance-add-keyword',
    ignoranceDescriptionTextarea: '#ignorance-description',
    table: '#alerts-table',
    pendingTable: '#pending-alerts-table',
    closedTable: '#closed-alerts-table'
};

export const elements = {
    boxes: document.querySelectorAll(selectors.box),
    box: id => {
        return document.getElementById(strings.box + id);
    },
    row: id => {
        return document.getElementById(strings.row + id);
    }
};