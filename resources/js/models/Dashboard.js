import * as axios from 'axios';
import Layout from './Layout';

export default class Dashboard {
    constructor() {
        this.layout = new Layout();
    }

    setClientID(id) {
        if (id) {
            this.clientID = id;
            this.layout.setPage('dashboard.client.index' + id);
        }
    }

    setUserRelated(userRelated) {
        if (userRelated) {
            this.userRelated = userRelated;
            this.layout.setPage('dashboard.user.index');
        }
    }

    async updateTile(label) {
        try {
            let url = '';
            if (this.userRelated) {
                url = `/dashboard/user/tile/${label}`;
            } else {
                url = `/dashboard/tile/${label}`;
                if (this.clientID) {
                    url = `${url}/${this.clientID}`;
                }
            }
            return axios.get(url);
        } catch (error) {
            console.log(error);
        }
    }

    async updateClientTile() {
        try {
            return this.updateTile('client').then(response => {
                this.clientTile = response.data;
            });
        } catch (error) {
            console.log(error);
        }
    }

    async updateClientsTile() {
        try {
            return this.updateTile('clients').then(response => {
                this.clientsTile = response.data;
            });
        } catch (error) {
            console.log(error);
        }
    }

    async updateWatchedAutomatedProcessesTile() {
        try {
            return this.updateTile('watched-automated-processes').then(response => {
                this.watchedAutomatedProcessesTile = response.data;
            });
        } catch (error) {
            console.log(error);
        }
    }

    async updateRobotsTile() {
        try {
            return this.updateTile('robots').then(response => {
                this.robotsTile = response.data;
            });
        } catch (error) {
            console.log(error);
        }
    }

    async updateNotClosedAlertsTile() {
        try {
            return this.updateTile('alerts-not-closed').then(response => {
                this.notClosedAlertsTile = response.data;
            });
        } catch (error) {
            console.log(error);
        }
    }

    async updateUnderRevisionAlertsTile() {
        try {
            return this.updateTile('alerts-under-revision').then(response => {
                this.underRevisionAlertsTile = response.data;
            });
        } catch (error) {
            console.log(error);
        }
    }

    async updateClosedAlertsTile() {
        try {
            return this.updateTile('alerts-closed').then(response => {
                this.closedAlertsTile = response.data;
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
            return axios.get(url).then(response => {
                if (!closed) {
                    this.pendingAlertsTable = response.data;
                } else {
                    this.closedAlertsTable = response.data;
                }
            });
        } catch (error) {
            console.log(error);
        }
    }
}