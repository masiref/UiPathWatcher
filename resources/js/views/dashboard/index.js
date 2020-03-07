import Dashboard from '../../models/Dashboard';

import * as _base from '../base';

import * as base from './base';
import * as view from './view';

import * as layoutController from '../layout/index';
import * as alertController from '../alert/index';
import * as clientController from '../client/index';
import * as watchedAutomatedProcessController from '../watched-automated-process/index';

export let dashboard = undefined;

export const init = () => {
    try {
        dashboard = new Dashboard();
        const clientID = _base.getClientIDFromURL(window.location.href);
        if (clientID) {
            dashboard.setClientID(clientID);
        }

        dashboard.setUserRelated(_base.isUserRelatedURL(window.location.href));

        base.elements.dashboard.addEventListener('click', e => {
            alertController.init(dashboard, e.target);
        });

        setInterval(() => {
            update()
        }, 30000);
    } catch (error) {
        console.log(`Failed to init dashboard: ${error}`);
    }
};

export const update = async () => {
    try {
        let promises = [
            layoutController.updateMenu(dashboard),
            layoutController.updateSidebar(dashboard),
            updateTiles(),
            alertController.updatePendingTable(dashboard),
            alertController.updateClosedTable(dashboard)
        ];

        if (dashboard.clientID) {
            promises.push(watchedAutomatedProcessController.updateAll());
        } else {
            if (!dashboard.userRelated) {
                promises.push(clientController.updateAll());
            }
        }

        return Promise.all(promises);
    } catch (error) {
        console.log(`Failed to update dashboard: ${error}`);
    }
};

export const updateTiles = async () => {
    try {
        let promises = undefined;
        if (dashboard.userRelated) {
            promises = [ updateAlertsTiles() ];
        } else {
            if (dashboard.clientID) {
                promises = [ updateClientTile() ]
            } else {
                promises = [ updateClientsTile() ]
            }
            promises.push(
                updateWatchedAutomatedProcessesTile(),
                updateRobotsTile(),
                updateAlertsTiles()
            );
        }
        return Promise.all(promises);
    } catch (error) {
        console.log(error);
    }
};

export const updateAlertsTiles = async () => {
    try {
        return Promise.all([
            updateNotClosedAlertsTile(),
            updateUnderRevisionAlertsTile(),
            updateClosedAlertsTile()
        ]);
    } catch (error) {
        console.log(error);
    }
};

export const updateClientTile = async () => {
    try {
        if (base.elements.clientTile) {
            _base.renderLoader(base.elements.clientTile);
            return dashboard.updateClientTile().then(res => {
                base.elements.clientTile = view.updateClientTile(dashboard.clientTile);
            });
        }
    } catch (error) {
        console.log(error);
        _base.clearLoader(base.elements.clientsTile);
    }
};

export const updateClientsTile = async () => {
    try {
        if (base.elements.clientsTile) {
            _base.renderLoader(base.elements.clientsTile);
            return dashboard.updateClientsTile().then(res => {
                base.elements.clientsTile = view.updateClientsTile(dashboard.clientsTile);
            });
        }
    } catch (error) {
        console.log(error);
        _base.clearLoader(base.elements.clientsTile);
    }
};

export const updateWatchedAutomatedProcessesTile = async () => {
    try {
        _base.renderLoader(base.elements.watchedAutomatedProcessesTile);
        return dashboard.updateWatchedAutomatedProcessesTile().then(res => {
            base.elements.watchedAutomatedProcessesTile = view
                .updateWatchedAutomatedProcessesTile(dashboard.watchedAutomatedProcessesTile);
        });
    } catch (error) {
        console.log(error);
        _base.clearLoader(base.elements.watchedAutomatedProcessesTile);
    }
};

export const updateRobotsTile = async () => {
    try {
        _base.renderLoader(base.elements.robotsTile);
        return dashboard.updateRobotsTile().then(res => {
            base.elements.robotsTile = view.updateRobotsTile(dashboard.robotsTile);
        });
    } catch (error) {
        console.log(error);
        _base.clearLoader(base.elements.robotsTile);
    }
};

export const updateNotClosedAlertsTile = async () => {
    try {
        _base.renderLoader(base.elements.notClosedAlertsTile);
        return dashboard.updateNotClosedAlertsTile().then(res => {
            base.elements.notClosedAlertsTile = view.updateNotClosedAlertsTile(dashboard.notClosedAlertsTile);
        });
    } catch (error) {
        console.log(error);
        _base.clearLoader(base.elements.notClosedAlertsTile);
    }
};

export const updateUnderRevisionAlertsTile = async () => {
    try {
        _base.renderLoader(base.elements.underRevisionAlertsTile);
        return dashboard.updateUnderRevisionAlertsTile().then(res => {
            base.elements.underRevisionAlertsTile = view.updateUnderRevisionAlertsTile(dashboard.underRevisionAlertsTile);
        });
    } catch (error) {
        console.log(error);
        _base.clearLoader(base.elements.underRevisionAlertsTile);
    }
};

export const updateClosedAlertsTile = async () => {
    try {
        _base.renderLoader(base.elements.closedAlertsTile);
        return dashboard.updateClosedAlertsTile().then(res => {
            base.elements.closedAlertsTile = view.updateClosedAlertsTile(dashboard.closedAlertsTile);
        });
    } catch (error) {
        console.log(error);
        _base.clearLoader(base.elements.closedAlertsTile);
    }
};