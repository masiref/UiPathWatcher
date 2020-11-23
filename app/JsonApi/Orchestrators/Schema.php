<?php

namespace App\JsonApi\Orchestrators;

use Neomerx\JsonApi\Schema\SchemaProvider;

class Schema extends SchemaProvider
{

    /**
     * @var string
     */
    protected $resourceType = 'orchestrators';

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
            'name' => $resource->name,
            'code' => $resource->code,
            'url' => $resource->url,
            'created-at' => $resource->created_at->toAtomString()
        ];
    }
}
