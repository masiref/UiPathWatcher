export const strings = {};

export const selectors = {
    dashboard: '.dashboard',
    quickBoard: {
        main: 'section.quick-board',
        item: '.quick-board--item',
        itemChildren: '.quick-board--item *',
        heading: '.quick-board--heading',
        headingChildren: '.quick-board--heading *',
        details: '.quick-board--details'
    },
    tiles: '.tile.is-parent',
    clientTile: '.tile.client',
    clientsTile: '.tile.clients',
    watchedAutomatedProcessesTile: '.tile.watched-automated-processes',
    robotsTile: '.tile.robots',
    notClosedAlertsTile: '.tile.alerts-not-closed',
    underRevisionAlertsTile: '.tile.alerts-under-revision',
    closedAlertsTile: '.tile.alerts-closed',
    sidebar: '.sidebar',
    menu: '.navbar.menu'
};

export const elements = {
    dashboard: document.querySelector(selectors.dashboard),
    quickBoard: {
        main: document.querySelector(selectors.quickBoard.main),
    },
    clientTile: document.querySelector(selectors.clientTile),
    clientsTile: document.querySelector(selectors.clientsTile),
    watchedAutomatedProcessesTile: document.querySelector(selectors.watchedAutomatedProcessesTile),
    robotsTile: document.querySelector(selectors.robotsTile),
    notClosedAlertsTile: document.querySelector(selectors.notClosedAlertsTile),
    underRevisionAlertsTile: document.querySelector(selectors.underRevisionAlertsTile),
    closedAlertsTile: document.querySelector(selectors.closedAlertsTile),
    sidebar: document.querySelector(selectors.sidebar),
    menu: document.querySelector(selectors.menu)
};