import * as axios from 'axios';

export default class WatchedAutomatedProcess {
    constructor(id) {
        this.id = id;
    }

    async updateMarkup() {
        try {
            return new Promise((resolve, reject) => {
                resolve(
                    axios.get(`/dashboard/watched-automated-process/element/${this.id}/true`).then(response => {
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
                    axios.get(`/api/watched-automated-processes/${this.id}`).then(response => {
                        this.data = response.data;
                        return this.updateMarkup();
                    })
                );
            });
        } catch (error) {
            console.log(error);
        }
    }
}