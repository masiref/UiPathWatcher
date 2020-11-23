<?php

namespace App\JsonApi\AlertTriggers;

use Neomerx\JsonApi\Schema\SchemaProvider;

class Schema extends SchemaProvider
{

    /**
     * @var string
     */
    protected $resourceType = 'alert-triggers';

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
            'watched-automated-process-id' => $resource->watched_automated_process_id,
            'title' => $resource->title,
            'active' => $resource->active,
            'ignored' => $resource->closed,
            'ignored-from' => $resource->ignored_from,
            'ignored-until' => $resource->ignored_until,
            'ignorance-description' => $resource->ignorance_description,
            'deleted' => $resource->deleted,
            'deleted-at' => $resource->deleted_at,
            'created-at' => $resource->created_at->toAtomString(),
            'updated-at' => $resource->updated_at->toAtomString(),
        ];
    }
}
