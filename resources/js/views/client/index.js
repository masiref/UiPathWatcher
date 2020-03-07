import Client from '../../models/Client';

import * as _base from '../base';
import * as base from '../client/base';
import * as view from '../client/view';

export const updateAll = async () => {
    try {
        const clients = document.querySelectorAll(base.selectors.boxes);
        let promises = [];
        clients.forEach(box => {
            _base.renderLoader(box);
            let client = new Client(box.dataset.id);
            promises.push(
                client.update().then(res => {
                    view.update(box.dataset.id, client.markup);
                })
            );
        });
        return Promise.all(promises);
    } catch (error) {
        console.log(error);
        clients.forEach(box => {
            _base.clearLoader(box);
        });
    }
};