import * as axios from 'axios';

export default class WatchedAutomatedProcess {
    constructor(id = null) {
        if (id) {
            this.id = id;
        }
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

    async get() {
        try {
            return new Promise((resolve, reject) => {
                resolve(
                    axios.get(`/api/watched-automated-processes/${this.id}`).then(response => {
                        this.data = response.data;
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
                    this.get().then(response => {
                        return this.updateMarkup();
                    })
                );
            });
        } catch (error) {
            console.log(error);
        }
    }

    async save(
        client, name, code, operationalHandbookPageURL, kibanaDashboardURL, additionalInformation,
        runningPeriodMonday, runningPeriodTuesday, runningPeriodWednesday, runningPeriodThursday,
        runningPeriodFriday, runningPeriodSaturday, runningPeriodSunday, runningTimePeriodFrom,
        runningTimePeriodUntil, involvedProcesses, involvedRobots, involvedQueues) {
        try {
            return new Promise((resolve, reject) => {
                resolve(
                    axios.post('/api/watched-automated-processes', {
                        'client_id': client,
                        'name': name,
                        'code': code,
                        'operational_handbook_page_url': operationalHandbookPageURL,
                        'kibana_dashboard_url': kibanaDashboardURL,
                        'additional_information': additionalInformation,
                        'running_period_monday': runningPeriodMonday ? 1 : 0,
                        'running_period_tuesday': runningPeriodTuesday ? 1 : 0,
                        'running_period_wednesday': runningPeriodWednesday ? 1 : 0,
                        'running_period_thursday': runningPeriodThursday ? 1 : 0,
                        'running_period_friday': runningPeriodFriday ? 1 : 0,
                        'running_period_saturday': runningPeriodSaturday ? 1 : 0,
                        'running_period_sunday': runningPeriodSunday ? 1 : 0,
                        'running_period_time_from': runningTimePeriodFrom,
                        'running_period_time_until': runningTimePeriodUntil,
                        'involved_processes': involvedProcesses,
                        'involved_robots': involvedRobots,
                        'involved_queues': involvedQueues
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