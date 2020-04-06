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

        base.elements.dashboard.addEventListener('click', e => {
            const target = e.target;

            if (target.matches(`${base.selectors.quickBoard.heading}, ${base.selectors.quickBoard.headingChildren}`)) {
                const details = target.closest(base.selectors.quickBoard.item).querySelector(base.selectors.quickBoard.details);
                if (details.style.display === 'block') {
                    _base.animateCSS(details, 'fadeOut', () => {
                        details.style.display = 'none';
                    });
                } else {
                    _base.animateCSS(details, 'fadeIn');
                    details.style.display = 'block';
                }
            }
        });
        /*setTimeout(() => {
            update();
        }, 3000);*/

        setInterval(() => {
            update()
        }, 45000);
    } catch (error) {
        console.log(`Failed to init dashboard: ${error}`);
    }
};

export const update = async () => {
    try {
        let promises = [
            layoutController.update(dashboard.layout),
            updateQuickBoard(),
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
        return dashboard.updateTiles().then(response => {
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

export const updateQuickBoard = async () => {
    let quickBoard = base.elements.quickBoard.main;
    try {
        _base.renderLoader(quickBoard);
        return dashboard.updateQuickBoard().then(response => {
            quickBoard = view.updateQuickBoard(dashboard.quickBoard);
        });
    } catch (error) {
        _base.clearLoader(quickBoard);
        console.log(error);
    }
};