export default class AlertDefinition {
    constructor(rank) {
        this.rank = rank;
        this.level = 'info';
        this.rules = [];
    }

    findRule(rank) {
        return this.rules.find(item => {
            return item.rank === rank;
        });
    }

    removeRule(rank) {
        this.rules = this.rules.filter(item => {
            return item.rank !== rank;
        });
        // change rank of all items when > rank
        this.rules = this.rules.map(item => {
            if (item.rank > rank) {
                item.rank = item.rank - 1;
            }
            return item;
        });
    }
}