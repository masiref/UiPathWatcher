import WatchedAutomatedProcess from '../../models/WatchedAutomatedProcess';

import * as _base from '../base';

import * as base from './base';
import * as view from './view';

export const updateAll = async (dashboard) => {
    const watchedAutomatedProcesses = document.querySelectorAll(base.selectors.boxes);
    try {
        const client = dashboard.getClient();
        watchedAutomatedProcesses.forEach(box => {
            _base.renderLoader(box);
        });
        return new Promise((resolve, reject) => {
            resolve(
                client.updateWatchedAutomatedProcesses().then(res => {
                    client.watchedAutomatedProcesses.forEach(watchedAutomatedProcess => {
                        view.update(watchedAutomatedProcess[0], watchedAutomatedProcess[1]);
                    });
                })
            );
        });
    } catch (error) {
        console.log(error);
        watchedAutomatedProcesses.forEach(box => {
            _base.clearLoader(box);
        });
    }
};