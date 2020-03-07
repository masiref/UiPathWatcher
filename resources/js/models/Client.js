import * as view from '../views/client/view';
import * as axios from 'axios';

export default class Client {
    constructor(id) {
        this.id = id;
    }

    async updateMarkup() {
        try {
            return axios.get(`/dashboard/client/element/${this.id}`).then(response => {
                this.markup = response.data;
            });
        } catch (error) {
            console.log(error);
        }
    }

    async update() {
        try {
            return axios.get(`/api/clients/${this.id}`).then(response => {
                this.data = response.data;
                return this.updateMarkup();
            });
        } catch (error) {
            console.log(error);
        }
    }
}