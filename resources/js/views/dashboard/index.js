import Dashboard from '../../models/Dashboard';

import * as _base from '../base';

import * as base from './base';
import * as view from './view';

import * as layoutController from '../layout/index';
import * as alertController from '../alert/index';
import * as clientController from '../client/index';
import * as watchedAutomatedProcessController from '../watched-automated-process/index';

export let dashboard;

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

        setTimeout(() => {
            update();
        }, 3000);

        /*setInterval(() => {
            update()
        }, 30000);*/
    } catch (error) {
        console.log(`Failed to init dashboard: ${error}`);
    }
};

export const update = async () => {
    try {
        let promises = [
            layoutController.updateMenu(dashboard.layout),
            layoutController.updateSidebar(dashboard.layout),
            updateTiles(),
            alertController.updatePendingTable(dashboard),
            alertController.updateClosedTable(dashboard)
        ];

        if (dashboard.clientID) {
            promises.push(watchedAutomatedProcessController.updateAll(dashboard));
        } else {
            if (!dashboard.userRelated) {
                promises.push(clientController.updateAll(dashboard));
            }
        }

        return Promise.all(promises);
    } catch (error) {
        console.log(`Failed to update dashboard: ${error}`);
    }
};

export const updateTiles = async () => {
    const tiles = document.querySelectorAll(base.selectors.tiles);
    try {
        tiles.forEach(tile => {
            _base.renderLoader(tile);
        });
        return dashboard.updateTiles().then(res => {
            base.elements.clientTile = view.updateClientTile(dashboard.clientTile);
            base.elements.clientsTile = view.updateClientsTile(dashboard.clientsTile);
            base.elements.watchedAutomatedProcessesTile = view
                            .updateWatchedAutomatedProcessesTile(dashboard.watchedAutomatedProcessesTile);
            base.elements.robotsTile = view.updateRobotsTile(dashboard.robotsTile);
            base.elements.notClosedAlertsTile = view.updateNotClosedAlertsTile(dashboard.notClosedAlertsTile);
            base.elements.underRevisionAlertsTile = view.updateUnderRevisionAlertsTile(dashboard.underRevisionAlertsTile);
            base.elements.closedAlertsTile = view.updateClosedAlertsTile(dashboard.closedAlertsTile);
        });
    } catch (error) {
        tiles.forEach(tile => {
            _base.clearLoader(tile);
        });
        console.log(error);
    }
};