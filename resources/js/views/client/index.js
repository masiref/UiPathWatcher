import Client from '../../models/Client';

import * as _base from '../base';
import * as base from './base';
import * as view from './view';

export const updateAll = async (dashboard) => {
    const clients = document.querySelectorAll(base.selectors.boxes);
    try {
        clients.forEach(client => {
            _base.renderLoader(client);
        });
        return new Promise((resolve, reject) => {
            resolve(
                dashboard.updateClients().then(res => {
                    dashboard.clients.forEach(client => {
                        view.update(client[0], client[1]);
                    });
                })
            );
        });
    } catch (error) {
        clients.forEach(client => {
            _base.clearLoader(client);
        });
        console.log(error);
    }
};