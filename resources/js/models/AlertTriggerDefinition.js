export default class AlertTriggerDefinition {
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

    isValid() {
        let valid = this.rules.length > 0;
        this.rules.forEach(rule => {
            valid = valid && rule.valid;
        });
        return valid;
    }
}