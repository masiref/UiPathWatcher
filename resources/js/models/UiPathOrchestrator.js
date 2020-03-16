import * as axios from 'axios';

export default class UiPathOrchestrator {
    constructor(id = null) {
        if (id) {
            this.id = id;
        }
    }

    async save(name, url, tenant, apiUserUsername, apiUserPassword, kibanaUrl, kibanaIndex) {
        try {
            return new Promise((resolve, reject) => {
                resolve(
                    axios.post('/api/ui-path-orchestrator', {
                        'name': name,
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