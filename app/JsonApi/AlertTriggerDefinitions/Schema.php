<?php

namespace App\JsonApi\AlertTriggerDefinitions;

use Neomerx\JsonApi\Schema\SchemaProvider;

class Schema extends SchemaProvider
{

    /**
     * @var string
     */
    protected $resourceType = 'alert-trigger-definitions';

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
            'alert-trigger-id' => $resource->alert_trigger_id,
            'level' => $resource->level,
            'rank' => $resource->rank,
            'deleted' => $resource->deleted,
            'deleted_at' => $resource->deleted_at,
            'description' => $resource->description,
            'created-at' => $resource->created_at->toAtomString(),
            'updated-at' => $resource->updated_at->toAtomString(),
        ];
    }
}
