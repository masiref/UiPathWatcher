import * as axios from 'axios';
import Layout from './Layout';

export default class Configuration {
    constructor(page) {
        this.layout = new Layout(page);
    }

    async updateOrchestratorsTable() {
        try {
            return new Promise((resolve, reject) => {
                resolve(
                    axios.get('/configuration/orchestrator/table').then(response => {
                        this.orchestratorsTable = response.data;
                    })
                );
            });
        } catch (error) {
            console.log(error);
        }
    }

    async updateClientsTable() {
        try {
            return new Promise((resolve, reject) => {
                resolve(
                    axios.get('/configuration/client/table').then(response => {
                        this.clientsTable = response.data;
                    })
                );
            });
        } catch (error) {
            console.log(error);
        }
    }

    async updateWatchedAutomatedProcessesTable() {
        try {
            return new Promise((resolve, reject) => {
                resolve(
                    axios.get('/configuration/watched-automated-process/table').then(response => {
                        this.watchedAutomatedProcessesTable = response.data;
                    })
                );
            });
        } catch (error) {
            console.log(error);
        }
    }

    async getAlertTriggersDefaultAlertTriggerDetails(watchedAutomatedProcessId) {
        try {
            return new Promise((resolve, reject) => {
                resolve(
                    axios.get(`/configuration/alert-trigger/default-alert-trigger-details/${watchedAutomatedProcessId}`)
                );
            });
        } catch (error) {
            console.log(error);
        }
    }

    async getAlertTriggersDefaultAlertTriggerDefinition(rank) {
        try {
            return new Promise((resolve, reject) => {
                resolve(
                    axios.get(`/configuration/alert-trigger/default-alert-trigger-definition/${rank}`)
                );
            });
        } catch (error) {
            console.log(error);
        }
    }

    async getAlertTriggersDefaultAlertTriggerRule(watchedAutomatedProcess, rank, type = 'none') {
        try {
            return new Promise((resolve, reject) => {
                resolve(
                    axios.get(`/configuration/alert-trigger/default-alert-trigger-rule/${watchedAutomatedProcess}/${rank}/${type}`)
                );
            });
        } catch (error) {
            console.log(error);
        }
    }
}