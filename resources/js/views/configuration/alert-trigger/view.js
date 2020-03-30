import { strings, elements, selectors } from './base';
import * as _base from '../../base';
import bulmaCalendar from 'bulma-calendar';

export const updateTable = markup => {
    const table = _base.update(
        document.querySelector(selectors.table).closest(_base.selectors.tableDataTablesWrapper),
        markup
    );
    $(selectors.table).DataTable();
    return table;
}

export const updateActiveStepContent = markup => {
    document.querySelector(selectors.activeStepContent).innerHTML = markup;
}

export const processSelection = {
    updateProcesses: processes => {
        processes.forEach(process_ => {
            let option = document.createElement('option');
            option.text = process_['name'];
            option.value = process_['id'];
            elements.processSelection.watchedProcessSelect.add(option);
        });
        elements.processSelection.watchedProcessSelect.disabled = false;
    }
};

export const details = {
    updateAlertDefinitionsCount: count => {
        document.querySelector(selectors.details.alertDefinition.count).innerHTML = count;
    },
    addAlertDefinition: markup => {
        document.querySelector(selectors.details.alertDefinition.list).appendChild(
            _base.htmlToElement(markup)
        );
    },
    deleteAlertDefinition: alertDefinitionItem => {
        const rank = parseInt(alertDefinitionItem.dataset.rank);
        document.querySelector(selectors.details.alertDefinition.list).removeChild(
            alertDefinitionItem
        );
        // change rank of all alert definition items when > rank
        const alertDefinitionItems = document.querySelectorAll(selectors.details.alertDefinition.item);
        alertDefinitionItems.forEach(alertDefinitionItem => {
            const alertDefinitionItemRank = parseInt(alertDefinitionItem.dataset.rank);
            if (alertDefinitionItemRank > rank) {
                alertDefinitionItem.dataset.rank = alertDefinitionItemRank - 1;
                alertDefinitionItem.querySelector(selectors.details.alertDefinition.titleRank).innerHTML = alertDefinitionItemRank - 1;
            }
        });
    },
    updateAlertDefinitionLevel: (alertDefinitionItem, level) => {
        let state = `is-${level}`;
        let textState = `has-text-${level}`;
        const title = alertDefinitionItem.querySelector(selectors.details.alertDefinition.title);
        const titleIcon = alertDefinitionItem.querySelector(selectors.details.alertDefinition.titleIcon);
        _base.removeStates(alertDefinitionItem);
        _base.removeStates(title);
        _base.removeStates(titleIcon);
        alertDefinitionItem.classList.add(state);
        title.classList.add(textState);
        titleIcon.classList.add(textState);
    },
    addAlertRule: (alertDefinitionItem, markup) => {
        alertDefinitionItem.querySelector(selectors.details.alertDefinition.rule.list).appendChild(
            _base.htmlToElement(markup)
        );
    },
    deleteAlertRule: (alertDefinitionItem, ruleItem) => {
        const rank = parseInt(ruleItem.dataset.rank);
        alertDefinitionItem.querySelector(selectors.details.alertDefinition.rule.list).removeChild(
            ruleItem
        );
        // change rank of all rule items when > rank
        const ruleItems = alertDefinitionItem.querySelectorAll(selectors.details.alertDefinition.rule.item);
        ruleItems.forEach(ruleItem => {
            const ruleItemRank = parseInt(ruleItem.dataset.rank);
            if (ruleItemRank > rank) {
                ruleItem.dataset.rank = ruleItemRank - 1;
                ruleItem.querySelector(selectors.details.alertDefinition.rule.titleRank).innerHTML = ruleItemRank - 1;
            }
        });
    },
    updateAlertRule: (ruleItem, markup, ruleType) => {
        ruleItem = _base.update(ruleItem, markup);
        const title = ruleItem.querySelector(selectors.details.alertDefinition.rule.title);
        const titleIcon = ruleItem.querySelector(selectors.details.alertDefinition.rule.titleIcon);
        _base.toggleSuccessDangerState(title, false, true);
        _base.toggleSuccessDangerState(titleIcon, false, true);
        
        if (ruleType !== 'none') {
            const calendarSelector = `
                ${selectors.details.alertDefinition.rule.item}[data-rank="${ruleItem.dataset.rank}"] ${selectors.details.alertDefinition.rule.timeSlotInput}
            `;
            const startTime = document.querySelector(calendarSelector).dataset.startTime.split(':');
            const endTime = document.querySelector(calendarSelector).dataset.endTime.split(':');
            bulmaCalendar.attach(calendarSelector, {
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
        }
        return ruleItem;
    }
};