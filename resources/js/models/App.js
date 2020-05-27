import * as axios from 'axios';
import Layout from './Layout';

export default class App {
    constructor() {
        this.layout = new Layout();
        this.notifications = [];
    }

    async shutdownAlertTriggers(reason) {
        try {
            return new Promise((resolve, reject) => {
                resolve(
                    axios.post('/app/shutdown-alert-triggers', {
                        reason: reason
                    })
                );
            });
        } catch (error) {
            console.log(error);
        }
    }

    async reactivateAlertTriggers(reason) {
        try {
            return new Promise((resolve, reject) => {
                resolve(
                    axios.post('/app/reactivate-alert-triggers', {
                        reason: reason
                    })
                );
            });
        } catch (error) {
            console.log(error);
        }
    }

    async getNotifications() {
        try {
            return new Promise((resolve, reject) => {
                resolve(
                    axios.get('/app/notifications')
                );
            });
        } catch (error) {
            console.log(error);
        }
    }
}