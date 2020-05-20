export default class AlertTriggerRule {
    constructor(
        id, definitionRank, rank, type = 'none', valid = false,
        standardParameters = {}, specificParameters = {}) {
        this.id = id;
        this.definitionRank = definitionRank;
        this.rank = rank;
        this.type = type;
        this.valid = valid;
        this.parameters = {
            standard: standardParameters,
            specific: specificParameters
        };
    }
}