export const strings = {};

export const selectors = {
    dashboard: '.dashboard',
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