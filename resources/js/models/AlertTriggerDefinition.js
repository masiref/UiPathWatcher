export default class AlertTriggerDefinition {
    constructor(id, rank, level = 'info', rules = [], description) {
        this.id = id;
        this.rank = rank;
        this.level = level;
        this.rules = rules;
        this.description = description;
        this.changed = false;
    }

    findRule(rank) {
        return this.rules.find(item => {
            return item.rank === rank;
        });
    }

    addRule(rule) {
        this.changed = true;
        this.rules.push(rule);
    }

    removeRule(rank) {
        this.changed = true;
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
        let valid = this.rules.length > 0 && this.description && this.description !== '';
        this.rules.forEach(rule => {
            valid = valid && rule.valid;
        });
        return valid;
    }
}