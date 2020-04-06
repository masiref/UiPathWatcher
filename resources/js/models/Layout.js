import * as axios from 'axios';

export default class Layout {
    constructor(page = 'dashboard.index') {
        this.page = page;
    }

    setPage(page) {
        this.page = page;
    }

    async update() {
        try {
            return Promise.all([
                this.updateMenu(),
                this.updateSidebar(),
                this.updateHero()
            ]);
        } catch (error) {
            console.log(error);
        }
    }

    async updateMenu() {
        try {
            return new Promise((resolve, reject) => {
                resolve(
                    axios.get(`/layout/menu/${this.page}`).then(response => {
                        this.menu = response.data;
                    })
                );
            });
        } catch (error) {
            console.log(error);
        }
    }

    async updateSidebar() {
        try {
            return new Promise((resolve, reject) => {
                resolve(
                    axios.get(`/layout/sidebar/${this.page}`).then(response => {
                        this.sidebar = response.data;
                    })
                );
            });
        } catch (error) {
            console.log(error);
        }
    }

    async updateHero() {
        try {
            return new Promise((resolve, reject) => {
                resolve(
                    axios.get('/layout/hero').then(response => {
                        this.hero = response.data;
                    })
                );
            });
        } catch (error) {
            console.log(error);
        }
    }
}