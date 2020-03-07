import * as view from '../views/alert/view';
import * as axios from 'axios';

export default class Alert {
    constructor(id) {
        this.id = id;
    }

    async updateMarkup() {
        try {
            const result = await axios.get(`/dashboard/alert/element/${this.id}`);
            this.markup = result.data;
        } catch (error) {
            console.log(error);
        }
    }

    async updateRowMarkup() {
        try {
            const result = await axios.get(`/dashboard/alert/table-row/${this.id}`);
            this.rowMarkup = result.data;
        } catch (error) {
            console.log(error);
        }
    }

    async update() {
        try {
            const result = await axios.get(`/api/alerts/${this.id}`);
            this.data = result.data;
            await this.updateMarkup();
            await this.updateRowMarkup();
        } catch (error) {
            console.log(error);
        }
    }

    async enterRevisionMode() {
        try {
            await axios.put(`/api/alerts/${this.id}`, {
                action: 'enter_revision_mode'
            });
            await this.updateMarkup();
            await this.updateRowMarkup();
        } catch (error) {
            console.log(error);
        }
    }

    async exitRevisionMode() {
        try {
            await axios.put(`/api/alerts/${this.id}`, {
                action: 'exit_revision_mode'
            });
            await this.updateMarkup();
            await this.updateRowMarkup();
        } catch (error) {
            console.log(error);
        }
    }

    async close(falsePositive, description) {
        try {
            await axios.put(`/api/alerts/${this.id}`, {
                action: 'close',
                falsePositive: falsePositive,
                description: description
            });
        } catch (error) {
            console.log(error);
        }
    }

    async ignore(from_, fromTime, to, toTime, description) {
        try {
            await axios.put(`/api/alerts/${this.id}`, {
                action: 'ignore',
                from_: from_,
                fromTime: fromTime,
                to: to,
                toTime: toTime,
                description: description
            });
        } catch (error) {
            console.log(error);
        }
    }
}