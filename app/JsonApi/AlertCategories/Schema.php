<?php

namespace App\JsonApi\AlertCategories;

use Neomerx\JsonApi\Schema\SchemaProvider;

class Schema extends SchemaProvider
{

    /**
     * @var string
     */
    protected $resourceType = 'alert-categories';

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
            'label' => $resource->label,
            'alerts' => $resource->alerts->pluck('id'),
            'created-at' => $resource->created_at->toAtomString()
        ];
    }
}
