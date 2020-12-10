export const strings = {
    addForm: 'add-form'
};

export const selectors = {
    table: 'table.alert-triggers',
    formsSection: '.forms-section',
    addFormSection: '.add-form-section',
    addForm: '#add-form',
    editFormSection: '.edit-form-section',
    editForm: '#edit-form',
    editFormButtonsSection: '.edit-buttons-section',
    steps: '.steps',
    activeStepContent: '.step-content.is-active',
    processSelection: {
        clientSelect: 'select.client',
        watchedProcessSelect: 'select.watched-automated-process'
    },
    details: {
        title: 'input.trigger-details--title-input',
        alertDefinition: {
            section: '.alert-definitions-section',
            list: '.alert-definitions-list',
            count: '.alert-definitions-section p.title span.tag',
            item: '.alert-definition-item',
            title: '.alert-definition-item .message-header span.definition-title',
            titleIcon: '.alert-definition-item .message-header span.icon',
            titleRank: 'span.trigger-details--alert-definition--rank',
            validityIcon: '.alert-definition-item .message-header span.validity-icon',
            addButton: 'button.trigger-details--alert-definition--add-button',
            addButtonChildren: 'button.trigger-details--alert-definition--add-button *',
            deleteButton: 'button.trigger-details--alert-definition--delete-button',
            deleteButtonChildren: 'button.trigger-details--alert-definition--delete-button *',
            descriptionInput: 'input.trigger-details--alert-definition--description-input',
            levelSelect: 'select.trigger-details--alert-definition--level-select',
            rule: {
                list: '.rules-list',
                item: '.rule-item',
                itemChildren: '.rule-item *',
                title: '.rule-item .title-level p.title',
                titleIcon: '.rule-item .title-level span.icon',
                titleRank: 'span.trigger-details--alert-rule--rank',
                addButton: 'button.trigger-details--alert-definition--add-rule-button',
                addButtonChildren: 'button.trigger-details--alert-definition--add-rule-button *',
                deleteButton: 'button.trigger-details--alert-definition--delete-rule-button',
                deleteButtonChildren: 'button.trigger-details--alert-definition--delete-rule-button *',
                typeSelect: 'select.trigger-details--alert-definition--rule--type-select',
                parameter: '.trigger-details--alert-definition--rule--parameter',
                timeSlotInput: 'input.trigger-details--alert-definition--rule--time-slot-input',
                relativeTimeSlotInput: 'input.trigger-details--alert-definition--rule--relative-time-slot-input',
                jobsDurationControls: {
                    minimalDurationInput: 'input.trigger-details--alert-definition--jobs-duration-rule--minimal-duration-input',
                    maximalDurationInput: 'input.trigger-details--alert-definition--jobs-duration-rule--maximal-duration-input'
                },
                faultedJobsPercentageControls: {
                    maximalPercentageInput: 'input.trigger-details--alert-definition--faulted-jobs-percentage-rule--maximal-percentage-input'
                },
                failedQueueItemsPercentageControls: {
                    maximalPercentageInput: 'input.trigger-details--alert-definition--failed-queue-items-percentage-rule--maximal-percentage-input'
                },
                elasticSearchQueryControls: {
                    searchQueryInput: 'input.trigger-details--alert-definition--elastic-search-query-rule--search-query-input',
                    lowerCountInput: 'input.trigger-details--alert-definition--elastic-search-query-rule--lower-count-input',
                    higherCountInput: 'input.trigger-details--alert-definition--elastic-search-query-rule--higher-count-input'
                },
                elasticSearchMultipleQueriesControls: {
                    leftSearchQueryInput: 'input.trigger-details--alert-definition--elastic-search-multiple-queries-comparison-rule--left-search-query-input',
                    rightSearchQueryInput: 'input.trigger-details--alert-definition--elastic-search-multiple-queries-comparison-rule--right-search-query-input',
                    comparisonOperatorSelect: 'select.trigger-details--alert-definition--elastic-search-multiple-queries-comparison-rule--comparison-operator-input'
                },
                triggeringDays: {
                    title: 'div.triggering-days-section .title-level p.title',
                    titleIcon: 'div.triggering-days-section .title-level span.icon',
                    mondayCheckbox: 'input.trigger-details--alert-definition--rule--triggering-day-monday',
                    tuesdayCheckbox: 'input.trigger-details--alert-definition--rule--triggering-day-tuesday',
                    wednesdayCheckbox: 'input.trigger-details--alert-definition--rule--triggering-day-wednesday',
                    thursdayCheckbox: 'input.trigger-details--alert-definition--rule--triggering-day-thursday',
                    fridayCheckbox: 'input.trigger-details--alert-definition--rule--triggering-day-friday',
                    saturdayCheckbox: 'input.trigger-details--alert-definition--rule--triggering-day-saturday',
                    sundayCheckbox: 'input.trigger-details--alert-definition--rule--triggering-day-sunday'
                },
                /*kibanaMetricVisualizationControls: {
                    metricVisualizationSelect: 'select.trigger-details--alert-definition--kibana-metric-visualization-rule--metric-visualization-select',
                    lowerCountInput: 'input.trigger-details--alert-definition--kibana-metric-visualization-rule--lower-count-input',
                    higherCountInput: 'input.trigger-details--alert-definition--kibana-metric-visualization-rule--higher-count-input'
                },*/
                involvedEntitiesControls: {
                    processes: {
                        title: 'div.involved-processes-section .title-level p.title',
                        titleIcon: 'div.involved-processes-section .title-level span.icon',
                        count: 'div.involved-processes-section .title-level p.title span.tag',
                        switch: 'input.trigger-details--alert-definition--involved-processes--process-switch'
                    },
                    robots: {
                        title: 'div.involved-robots-section .title-level p.title',
                        titleIcon: 'div.involved-robots-section .title-level span.icon',
                        count: 'div.involved-robots-section .title-level p.title span.tag',
                        switch: 'input.trigger-details--alert-definition--involved-robots--robot-switch'
                    },
                    queues: {
                        title: 'div.involved-queues-section .title-level p.title',
                        titleIcon: 'div.involved-queues-section .title-level span.icon',
                        count: 'div.involved-queues-section .title-level p.title span.tag',
                        switch: 'input.trigger-details--alert-definition--involved-queues--queue-switch'
                    }
                }
            }
        }
    },
    confirmation: {
        notification: '.notification',
        activateButton: 'button.trigger-details--confirmation--activate-button',
        activateButtonChildren: 'button.trigger-details--confirmation--activate-button *',
        closeButton: 'button.trigger-details--confirmation--close-button',
        closeButtonChildren: 'button.trigger-details--confirmation--close-button *'
    },
    activateButton: 'button.activate',
    activateButtonChildren: 'button.activate *',
    disableButton: 'button.disable',
    disableButtonChildren: 'button.disable *',
    ignoreButton: 'button.ignore',
    ignoreButtonChildren: 'button.ignore *',
    acknowledgeButton: 'button.acknowledge',
    acknowledgeButtonChildren: 'button.acknowledge *',
    cancelButton: 'button.cancel',
    cancelButtonChildren: 'button.cancel *',
    removeButton: 'button.remove',
    removeButtonChildren: 'button.remove *',
    restoreButton: 'button.restore',
    restoreButtonChildren: 'button.restore *'
};

export const elements = {
    table: document.querySelector(selectors.table),
    formsSection: document.querySelector(selectors.formsSection),
    addForm: document.querySelector(selectors.addForm),
    steps: document.querySelector(selectors.steps),
    processSelection: {
        clientSelect: document.querySelector(selectors.processSelection.clientSelect),
        watchedProcessSelect: document.querySelector(selectors.processSelection.watchedProcessSelect)
    },
    details: {
        alertDefinition: {
            item: rank => {
                return document.querySelector(`${selectors.details.alertDefinition.item}[data-rank="${rank}"]`);
            },
            rule: {
                item: (alertDefinitionItem, rank) => {
                    return alertDefinitionItem.querySelector(`${selectors.details.alertDefinition.rule.item}[data-rank="${rank}"]`);
                }
            }
        }
    }
};