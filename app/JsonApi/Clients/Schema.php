<?php

namespace App\JsonApi\Clients;

use Neomerx\JsonApi\Schema\SchemaProvider;

class Schema extends SchemaProvider
{

    /**
     * @var string
     */
    protected $resourceType = 'clients';

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
            'ui-path-orchestrator-id' => $resource->ui_path_orchestrator_id,
            'name' => $resource->name,
            'code' => $resource->code,
            'ui-path-orchestrator-tenant' => $resource->ui_path_orchestrator_tenant,
            'elastic-search-url' => $resource->elastic_search_url,
            'elastic-search-index' => $resource->elastic_search_index,
            'created-at' => $resource->created_at->toAtomString(),
            'updated-at' => $resource->updated_at->toAtomString(),
        ];
    }
}
