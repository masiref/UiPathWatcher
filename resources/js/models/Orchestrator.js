import * as axios from 'axios';

export default class Orchestrator {
    constructor(id = null) {
        if (id) {
            this.id = id;
        }
    }

    async save(name, code, url, tenant, apiUserUsername, apiUserPassword, elasticSearchUrl, elasticSearchIndex) {
        try {
            return new Promise((resolve, reject) => {
                resolve(
                    axios.post('/api/ui-path-orchestrators', {
                        'name': name,
                        'code': code,
                        'url': url,
                        'tenant': tenant,
                        'api_user_username': apiUserUsername,
                        'api_user_password': apiUserPassword,
                        'elastic_search_url': elasticSearchUrl,
                        'elastic_search_index': elasticSearchIndex
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

    async update(name, code, url, tenant, apiUserUsername, apiUserPassword, elasticSearchUrl, elasticSearchIndex) {
        try {
            return new Promise((resolve, reject) => {
                resolve(
                    axios.patch(`/api/ui-path-orchestrators/${this.id}`, {
                        'name': name,
                        'code': code,
                        'url': url,
                        'tenant': tenant,
                        'api_user_username': apiUserUsername,
                        'api_user_password': apiUserPassword,
                        'elastic_search_url': elasticSearchUrl,
                        'elastic_search_index': elasticSearchIndex
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
                    axios.delete(`/api/ui-path-orchestrators/${this.id}`)
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
                    axios.get(`/configuration/orchestrator/edit/${this.id}`).then(response => {
                        this.editForm = response.data;
                    })
                );
            });
        } catch (error) {
            console.log(error);
        }
    }
}