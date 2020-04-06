import * as axios from 'axios';

export default class AlertTrigger {
    constructor(id = null) {
        if (id) {
            this.id = id;
        }
    }

    async save(watchedAutomatedProcess, title, definitions) {
        try {
            return new Promise((resolve, reject) => {
                resolve(
                    axios.post('/api/alert-triggers', {
                        'watched_automated_process_id': watchedAutomatedProcess,
                        'title': title,
                        'definitions': definitions
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

    async activate() {
        try {
            return new Promise((resolve, reject) => {
                resolve(
                    axios.patch(`/api/alert-triggers/${this.id}`, {
                        'active': 1
                    })
                );
            });
        } catch (error) {
            console.log(error);
        }
    }
}