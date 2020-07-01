import * as axios from 'axios';

export default class Alert {
    constructor(id) {
        this.id = id;
    }

    async updateMarkup() {
        try {
            return new Promise((resolve, reject) => {
                resolve(
                    axios.get(`/dashboard/alert/element/${this.id}`).then(result => {
                        this.markup = result.data;
                    })
                );
            });
        } catch (error) {
            console.log(error);
        }
    }

    async updateRowMarkup() {
        try {
            return new Promise((resolve, reject) => {
                resolve(
                    axios.get(`/dashboard/alert/table-row/${this.id}`).then(result => {
                        this.rowMarkup = result.data;
                    })
                );
            });
        } catch (error) {
            console.log(error);
        }
    }

    async closingFormModal() {
        try {
            return new Promise((resolve, reject) => {
                resolve(
                    axios.get(`/dashboard/alert/closing-form-modal/${this.id}`).then(result => {
                        this.closingFormModal = result.data;
                    })
                );
            });
        } catch (error) {
            console.log(error);
        }
    }

    async ignoranceFormModal() {
        try {
            return new Promise((resolve, reject) => {
                resolve(
                    axios.get(`/dashboard/alert/ignorance-form-modal/${this.id}`).then(result => {
                        this.ignoranceFormModal = result.data;
                    })
                );
            });
        } catch (error) {
            console.log(error);
        }
    }

    async timeline() {
        try {
            return new Promise((resolve, reject) => {
                resolve(
                    axios.get(`/dashboard/alert/timeline/${this.id}`).then(result => {
                        this.timeline = result.data;
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
                    axios.get(`/api/alerts/${this.id}`).then(async (result) => {
                        this.data = result.data;
                        await this.updateMarkup();
                        await this.updateRowMarkup();
                    })
                );
            });
        } catch (error) {
            console.log(error);
        }
    }

    async enterRevisionMode() {
        try {
            return new Promise((resolve, reject) => {
                resolve(
                    axios.put(`/api/alerts/${this.id}`, {
                        action: 'enter_revision_mode'
                    }).then(async (res) => {
                        await this.updateMarkup();
                        await this.updateRowMarkup();
                    })
                );
            });
        } catch (error) {
            console.log(error);
        }
    }

    async exitRevisionMode() {
        try {
            return new Promise((resolve, reject) => {
                resolve(
                    axios.put(`/api/alerts/${this.id}`, {
                        action: 'exit_revision_mode'
                    }).then(async (res) => {
                        await this.updateMarkup();
                        await this.updateRowMarkup();
                    })
                );
            });
        } catch (error) {
            console.log(error);
        }
    }

    async close(falsePositive, description) {
        try {
            return new Promise((resolve, reject) => {
                resolve(
                    axios.put(`/api/alerts/${this.id}`, {
                        action: 'close',
                        falsePositive: falsePositive,
                        description: description
                    })
                );
            });
        } catch (error) {
            console.log(error);
        }
    }

    async ignore(from_, fromTime, to, toTime, description) {
        try {
            return new Promise((resolve, reject) => {
                resolve(
                    axios.put(`/api/alerts/${this.id}`, {
                        action: 'ignore',
                        from_: from_,
                        fromTime: fromTime,
                        to: to,
                        toTime: toTime,
                        description: description
                    })
                );
            });
        } catch (error) {
            console.log(error);
        }
    }
}