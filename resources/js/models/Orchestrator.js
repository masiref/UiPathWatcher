import * as axios from 'axios';

export default class Orchestrator {
    constructor(id = null) {
        if (id) {
            this.id = id;
        }
    }

    async save(name, code, url, tenant, apiUserUsername, apiUserPassword, kibanaUrl, kibanaIndex) {
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
                        'kibana_url': kibanaUrl,
                        'kibana_index': kibanaIndex
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
}