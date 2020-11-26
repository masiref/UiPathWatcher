<?php

namespace App\JsonApi\AlertTriggerRules;

use Neomerx\JsonApi\Schema\SchemaProvider;

class Schema extends SchemaProvider
{

    /**
     * @var string
     */
    protected $resourceType = 'alert-trigger-rules';

    /**
     * @param $resource
     *      the domain record being serialized.
     * @return string
     */
    public function getId($resource)
    {
        return (string) $resource->getRouteKey();
    }

    /**
     * @param $resource
     *      the domain record being serialized.
     * @return array
     */
    public function getAttributes($resource)
    {
        return [
            'id_' => $resource->id,
            'alert-trigger-definition-id' => $resource->alert_trigger_definition_id,
            'type_' => $resource->type,
            'robots' => $resource->robots->pluck('id'),
            'processes' => $resource->processes->pluck('id'),
            'queues' => $resource->queues->pluck('id'),
            'created-at' => $resource->created_at->toAtomString(),
            'updated-at' => $resource->updated_at->toAtomString(),
        ];
    }
}
