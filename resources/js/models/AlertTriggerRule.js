export default class AlertTriggerRule {
    constructor(definitionRank, rank) {
        this.definitionRank = definitionRank;
        this.rank = rank;
        this.type = 'none';
        this.valid = false;
        this.parameters = {};
    }
}