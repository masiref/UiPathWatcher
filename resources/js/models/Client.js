import * as axios from 'axios';

export default class Client {
    constructor(id = null) {
        if (id) {
            this.id = id;
        }
    }

    /*async updateMarkup() {
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
    }*/

    async get() {
        try {
            return new Promise((resolve, reject) => {
                resolve(
                    axios.get(`/clients/${this.id}`).then(response => {
                        this.data = response.data;
                    })
                );
            });
        } catch (error) {
            console.log(error);
        }
    }

    /*async update() {
        try {
            return new Promise((resolve, reject) => {
                resolve(
                    this.get().then(response => {
                        return this.updateMarkup();
                    })
                );
            });
        } catch (error) {
            console.log(error);
        }
    }*/
    
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

    async save(name, code, orchestrator, orchestratorTenant, orchestratorApiUserUsername,
        orchestratorApiUserPassword, elasticSearchUrl, elasticSearchIndex,
        elasticSearchApiUserUsername, elasticSearchApiUserPassword) {
        try {
            return new Promise((resolve, reject) => {
                resolve(
                    axios.post('/clients', {
                        'name': name,
                        'code': code,
                        'ui_path_orchestrator_id': orchestrator,
                        'ui_path_orchestrator_tenant': orchestratorTenant,
                        'ui_path_orchestrator_api_user_username': orchestratorApiUserUsername,
                        'ui_path_orchestrator_api_user_password': orchestratorApiUserPassword,
                        'elastic_search_url': elasticSearchUrl,
                        'elastic_search_index': elasticSearchIndex,
                        'elastic_search_api_user_username': elasticSearchApiUserUsername,
                        'elastic_search_api_user_password': elasticSearchApiUserPassword
                    }).then(response => {
                        if (response.data) {
                            this.id = response.data.id;
                        }
                    })
                );
            });
        } catch (error) {
            console.log(error);
        }
    }

    async update(name, code, orchestrator, orchestratorTenant, orchestratorApiUserUsername,
        orchestratorApiUserPassword, elasticSearchUrl, elasticSearchIndex,
        elasticSearchApiUserUsername, elasticSearchApiUserPassword) {
        try {
            return new Promise((resolve, reject) => {
                resolve(
                    axios.patch(`/clients/${this.id}`, {
                        'name': name,
                        'code': code,
                        'ui_path_orchestrator_id': orchestrator,
                        'ui_path_orchestrator_tenant': orchestratorTenant,
                        'ui_path_orchestrator_api_user_username': orchestratorApiUserUsername,
                        'ui_path_orchestrator_api_user_password': orchestratorApiUserPassword,
                        'elastic_search_url': elasticSearchUrl,
                        'elastic_search_index': elasticSearchIndex,
                        'elastic_search_api_user_username': elasticSearchApiUserUsername,
                        'elastic_search_api_user_password': elasticSearchApiUserPassword
                    }).then(response => {
                        if (response.data) {
                            this.data = response.data;
                        }
                    })
                );
            });
        } catch (error) {
            console.log(error);
        }
    }

    async remove() {
        try {
            return new Promise((resolve, reject) => {
                resolve(
                    axios.delete(`/clients/${this.id}`)
                );
            });
        } catch (error) {
            console.log(error);
        }
    }

    async loadEditForm() {
        try {
            return new Promise((resolve, reject) => {
                resolve(
                    axios.get(`/configuration/client/edit/${this.id}`).then(response => {
                        this.editForm = response.data;
                    })
                );
            });
        } catch (error) {
            console.log(error);
        }
    }

    async getProcessesFromOrchestrator() {
        try {
            return new Promise((resolve, reject) => {
                resolve(
                    axios.get(`/configuration/orchestrator/processes/${this.id}`)
                );
            });
        } catch (error) {
            console.log(error);
        }
    }

    async getRobotsFromOrchestrator() {
        try {
            return new Promise((resolve, reject) => {
                resolve(
                    axios.get(`/configuration/orchestrator/robots/${this.id}`)
                );
            });
        } catch (error) {
            console.log(error);
        }
    }

    async getQueuesFromOrchestrator() {
        try {
            return new Promise((resolve, reject) => {
                resolve(
                    axios.get(`/configuration/orchestrator/queues/${this.id}`)
                );
            });
        } catch (error) {
            console.log(error);
        }
    }
}