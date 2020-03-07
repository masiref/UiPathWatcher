import WatchedAutomatedProcess from '../../models/WatchedAutomatedProcess';

import * as _base from '../base';

import * as base from './base';
import * as view from './view';

export const updateAll = async () => {
    try {
        const watchedAutomatedProcesses = document.querySelectorAll(base.selectors.boxes);
        let promises = [];
        watchedAutomatedProcesses.forEach(box => {
            _base.renderLoader(box);
            let watchedAutomatedProcess = new WatchedAutomatedProcess(box.dataset.id);
            promises.push(
                watchedAutomatedProcess.update().then(res => {
                    view.update(box.dataset.id, watchedAutomatedProcess.markup);
                })
            );
        });
        return Promise.all(promises);
    } catch (error) {
        console.log(error);
        watchedAutomatedProcesses.forEach(box => {
            _base.clearLoader(box);
        });
    }
};