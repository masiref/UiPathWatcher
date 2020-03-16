import * as axios from 'axios';
import Layout from './Layout';

export default class Dashboard {
    constructor() {
        this.layout = new Layout('configuration.orchestrator.index');
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
}