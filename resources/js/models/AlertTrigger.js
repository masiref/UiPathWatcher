import * as axios from 'axios';
import AlertTriggerDefinition from './AlertTriggerDefinition';
import AlertTriggerRule from './AlertTriggerRule';

export default class AlertTrigger {
    constructor(id = null) {
        //if (id) {
            this.id = id;
            //this.get();
        //} else {
            this.title = '';
            this.definitions = [];
            this.changed = false;
            this.valid = true;
            this.finished = false;
        //}
    }

    findDefinition(rank) {
        return this.definitions.find(item => {
            return item.rank === rank;
        });
    };

    addDefinition(definition) {
        this.changed = true;
        this.definitions.push(definition);
    }

    removeDefinition(rank) {
        this.changed = true;
        this.definitions = this.definitions.filter(item => {
            return item.rank !== rank;
        });
        // change rank of all items when > rank
        this.definitions = this.definitions.map(item => {
            if (item.rank > rank) {
                item.rank = item.rank - 1;
            }
            return item;
        });
    };

    isValid() {
        let valid = true;
        this.definitions.forEach(alertDefinition => {
            valid = valid && alertDefinition.isValid();
        });
        this.valid = valid;
        return this.valid;
    };

    hasChanged() {
        if (!this.changed) {
            this.definitions.forEach(alertDefinition => {
                this.changed = this.changed || alertDefinition.changed;
            });
        }
        return this.changed;
    };

    setUnchanged() {
        this.changed = false;
        this.definitions.forEach(alertDefinition => {
            alertDefinition.changed = false;
        });
    }

    loadDefinitions(data = null) {
        let definitions = [];
        if (data) {
            this.data = data;
        }
        if (this.data) {
            this.data.definitions.forEach(definition => {
                if (!definition.deleted) {
                    const definitionId = definition.id;
                    const definitionRank = definition.rank;
                    const level = definition.level;
                    let rules = [];
                    definition.rules.forEach(rule => {
                        if (!rule.deleted) {
                            const ruleId = rule.id;
                            const rank = rule.rank;
                            const type = rule.type;
                            const standardParameters = {
                                timeSlot:{
                                    from: rule.time_slot_from,
                                    to: rule.time_slot_until
                                },
                                relativeTimeSlot: rule.relative_time_slot_duration,
                                triggeringDays: {
                                    monday: rule.is_triggered_on_monday,
                                    tuesday: rule.is_triggered_on_tuesday,
                                    wednesday: rule.is_triggered_on_wednesday,
                                    thursday: rule.is_triggered_on_thursday,
                                    friday: rule.is_triggered_on_friday,
                                    saturday: rule.is_triggered_on_saturday,
                                    sunday: rule.is_triggered_on_sunday
                                },
                                involvedEntities: {
                                    processes: rule.processes ? rule.processes.map(item => { return item.id }) : [],
                                    robots: rule.robots ? rule.robots.map(item => { return item.id }) : [],
                                    queues: rule.queues ? rule.queues.map(item => { return item.id }) : []
                                }
                            };
                            const specificParameters = rule.parameters;
                            const alertTriggerRule = new AlertTriggerRule(
                                ruleId, definitionRank, rank, type, true,
                                standardParameters, specificParameters
                            );
                            rules.push(alertTriggerRule);
                        }
                    });
                    const alertTriggerDefinition = new AlertTriggerDefinition(definitionId, definitionRank, level, rules);
                    definitions.push(alertTriggerDefinition);
                }
            });
        }
        return definitions;
    }

    async get() {
        try {
            return new Promise((resolve, reject) => {
                resolve(
                    axios.get(`/api/alert-triggers/${this.id}`).then(async (result) => {
                        this.data = result.data;
                        
                        this.valid = true;
                        this.title = this.data.title;
                        this.active = this.data.active === 1;
                        this.ignored = this.data.ignored === 1;
                        this.ignored_form = this.data.ignored_from ? new Date(this.data.ignored_from) : null;
                        this.ignored_until = this.data.ignored_until ? new Date(this.data.ignored_until) : null;
                        this.ignorance_description = this.data.ignorance_description;
                        this.deleted = this.data.deleted;
                        this.deleted_at = this.data.deleted_at ? new Date(this.data.deleted_at) : null;
                        this.created_at = this.data.created_at ? new Date(this.data.created_at) : null;
                        this.updated_at = this.data.updated_at ? new Date(this.data.updated_at) : null;
                        this.definitions = this.loadDefinitions();
                    })
                );
            });
        } catch (error) {
            console.log(error);
        }
    }

    async save(watchedAutomatedProcess) {
        try {
            return new Promise((resolve, reject) => {
                resolve(
                    axios.post('/api/alert-triggers', {
                        'watched_automated_process_id': watchedAutomatedProcess,
                        'title': this.title,
                        'definitions': this.definitions
                    }).then(response => {
                        if (response.data) {
                            this.id = response.data.id;
                            this.setUnchanged();
                        }
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
                    axios.patch(`/api/alert-triggers/${this.id}`, {
                        'title': this.title,
                        'definitions': this.definitions
                    }).then(response => {
                        console.log(response);
                        if (response.data) {
                            this.setUnchanged();
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
                    axios.delete(`/api/alert-triggers/${this.id}`).then(response => {
                        this.deleted = true;
                        this.setUnchanged();
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

    async disable() {
        try {
            return new Promise((resolve, reject) => {
                resolve(
                    axios.patch(`/api/alert-triggers/${this.id}`, {
                        'active': 0
                    })
                );
            });
        } catch (error) {
            console.log(error);
        }
    }

    async ignore() {
        try {
            return new Promise((resolve, reject) => {
                resolve(
                    axios.patch(`/api/alert-triggers/${this.id}`, {
                        'ignored': 1
                    })
                );
            });
        } catch (error) {
            console.log(error);
        }
    }

    async acknowledge() {
        try {
            return new Promise((resolve, reject) => {
                resolve(
                    axios.patch(`/api/alert-triggers/${this.id}`, {
                        'ignored': 0
                    })
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
                    axios.get(`/configuration/alert-trigger/edit/${this.id}`).then(response => {
                        this.editForm = response.data;
                    })
                );
            });
        } catch (error) {
            console.log(error);
        }
    }

    async loadEditFormButtons() {
        try {
            return new Promise((resolve, reject) => {
                resolve(
                    axios.get(`/configuration/alert-trigger/edit-buttons/${this.id}`).then(response => {
                        this.editFormButtons = response.data;
                    })
                );
            });
        } catch (error) {
            console.log(error);
        }
    }
}