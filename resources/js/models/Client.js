import * as axios from 'axios';

export default class Client {
    constructor(id) {
        this.id = id;
    }

    async updateMarkup() {
        try {
            return new Promise((resolve, reject) => {
                resolve(
                    axios.get(`/dashboard/client/element/${this.id}`).then(response => {
                        this.markup = response.data;
                    })
                );
            });
        } catch (error) {
            console.log(error);
        }
    }

    async update() {
        try {
            return new Promise((resolve, reject) => {
                resolve(
                    axios.get(`/api/clients/${this.id}`).then(response => {
                        this.data = response.data;
                        return this.updateMarkup();
                    })
                );
            });
        } catch (error) {
            console.log(error);
        }
    }
    
    async updateWatchedAutomatedProcesses() {
        try {
            return new Promise((resolve, reject) => {
                resolve(
                    axios.get(`/dashboard/client/${this.id}/watched-automated-process/elements/1`).then(response => {
                        this.watchedAutomatedProcesses = Object.keys(response.data).map(key => {
                            return [ Number(key), response.data[key] ];
                        });
                    })
                );
            });
        } catch (error) {
            console.log(error);
        }
    }
}