import * as axios from 'axios';

export default class RobotTool {
    constructor(id = null) {
        if (id) {
            this.id = id;
        }
    }

    async save(label, processName, color) {
        try {
            return new Promise((resolve, reject) => {
                resolve(
                    axios.post('/ui-path-robot-tools', {
                        'label': label,
                        'process_name': processName,
                        'color': color
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

    async update(label, processName, color) {
        try {
            return new Promise((resolve, reject) => {
                resolve(
                    axios.patch(`/ui-path-robot-tools/${this.id}`, {
                        'label': label,
                        'process_name': processName,
                        'color': color
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
                    axios.delete(`/ui-path-robot-tools/${this.id}`)
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
                    axios.get(`/configuration/extension/edit/${this.id}`).then(response => {
                        this.editForm = response.data;
                    })
                );
            });
        } catch (error) {
            console.log(error);
        }
    }
}