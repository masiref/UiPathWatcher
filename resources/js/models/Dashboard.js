import * as axios from 'axios';
import Layout from './Layout';
import Client from './Client';

export default class Dashboard {
    constructor() {
        this.layout = new Layout();
    }

    setClientID(id) {
        if (id) {
            this.clientID = id;
            this.layout.setPage('dashboard.client.index.' + id);
        }
    }

    getClient() {
        if (this.clientID) {
            return new Client(this.clientID);
        }
        return null;
    }

    setUserRelated(userRelated) {
        if (userRelated) {
            this.userRelated = userRelated;
            this.layout.setPage('dashboard.user.index');
        }
    }

    async updateTiles() {
        try {
            let url = '';
            if (this.userRelated) {
                url = `/dashboard/user/tiles`;
            } else {
                url = `/dashboard/tiles`;
                if (this.clientID) {
                    url = `${url}/${this.clientID}`;
                }
            }
            return new Promise((resolve, reject) => {
                resolve(
                    axios.get(url).then(response => {
                        this.clientTile = response.data.client;
                        this.clientsTile = response.data.clients;
                        this.watchedAutomatedProcessesTile = response.data['watched-automated-processes'];
                        this.robotsTile = response.data.robots;
                        this.notClosedAlertsTile = response.data['alerts-not-closed'];
                        this.underRevisionAlertsTile = response.data['alerts-under-revision'];
                        this.closedAlertsTile = response.data['alerts-closed'];
                    })
                );
            });
        } catch (error) {
            console.log(error);
        }
    }
    
    async updateClients() {
        try {
            return new Promise((resolve, reject) => {
                resolve(
                    axios.get(`/dashboard/client/elements`).then(response => {
                        this.clients = Object.keys(response.data).map(key => {
                            return [ Number(key), response.data[key] ];
                        });
                    })
                );
            });
        } catch (error) {
            console.log(error);
        }
    }

    async updateAlertsTable(closed, id) {
        try {
            closed = closed ? 1 : 0;
            let url = `/dashboard/alert/table/${closed}/${id}`;
            if (this.clientID) {
                url = `/dashboard/client/alert/table/${this.clientID}/${closed}/${id}`;
            } else {
                if (this.userRelated) {
                    url = `/dashboard/user/alert/table/${closed}/${id}`;
                }
            }
            return new Promise((resolve, reject) => {
                resolve(
                    axios.get(url).then(response => {
                        if (!closed) {
                            this.pendingAlertsTable = response.data;
                        } else {
                            this.closedAlertsTable = response.data;
                        }
                    })
                );
            });
        } catch (error) {
            console.log(error);
        }
    }
}