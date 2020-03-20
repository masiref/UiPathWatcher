export const strings = {
    addForm: 'add-form'
};

export const selectors = {
    table: 'table.watched-automated-processes',
    addForm: '#add-form',
    clientSelect: 'select#client',
    nameInput: 'input#name',
    codeInput: 'input#code',
    operationalHandbookPageURLInput: 'input#operational_handbook_page_url',
    kibanaDashboardURLInput: 'input#kibana_dashboard_url',
    additionalInformationTextarea: 'textarea#additional_information',
    involvedProcessesSection: 'div.involved-processes-section',
    involvedProcessesSectionTitle: 'div.involved-processes-section h1.subtitle',
    involvedProcessesCount: 'div.involved-processes-section span.tag',
    involvedProcessesTable: 'table.involved-processes-table',
    runningPeriodSection: 'div.running-period-section',
    runningPeriodSectionTitle: 'div.running-period-section h1.subtitle',
    runningPeriodCalendar: 'input#running_period_times',
    runningPeriodMondayCheckbox: 'input#running_period_monday',
    runningPeriodTuesdayCheckbox: 'input#running_period_tuesday',
    runningPeriodWednesdayCheckbox: 'input#running_period_wednesday',
    runningPeriodThursdayCheckbox: 'input#running_period_thursday',
    runningPeriodFridayCheckbox: 'input#running_period_friday',
    runningPeriodSaturdayCheckbox: 'input#running_period_saturday',
    runningPeriodSundayCheckbox: 'input#running_period_sunday',
    createButton: 'button.create',
    createButtonChildren: 'button.create *',
    resetButton: 'button.reset',
    resetButtonChildren: 'button.reset *'
};

export const elements = {
    table: document.querySelector(selectors.table),
    addForm: document.querySelector(selectors.addForm),
    runningPeriodSection: document.querySelector(selectors.runningPeriodSection),
    runningPeriodSectionTitle: document.querySelector(selectors.runningPeriodSectionTitle),
    involvedProcessesSection: document.querySelector(selectors.involvedProcessesSection),
    involvedProcessesSectionTitle: document.querySelector(selectors.involvedProcessesSectionTitle),
    involvedProcessesCount: document.querySelector(selectors.involvedProcessesCount),
    createButton: document.querySelector(selectors.createButton),
    resetButton: document.querySelector(selectors.resetButton)
};