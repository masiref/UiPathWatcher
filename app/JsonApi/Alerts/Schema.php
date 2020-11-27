<?php

namespace App\JsonApi\Alerts;

use Neomerx\JsonApi\Schema\SchemaProvider;

class Schema extends SchemaProvider
{

    /**
     * @var string
     */
    protected $resourceType = 'alerts';

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
            'parent-id' => $resource->parent_id,
            'alert-trigger-id' => $resource->alert_trigger_id,
            'alert-trigger-definition-id' => $resource->alert_trigger_definition_id,
            'watched-automated-process-id' => $resource->watched_automated_process_id,
            'reviewer-id' => $resource->reviewer_id,
            'closed' => $resource->closed,
            'ignored' => $resource->closed,
            'under-revision' => $resource->closed,
            'closing-description' => $resource->closing_description,
            'false-positive' => $resource->false_positive,
            'auto-closed' => $resource->auto_closed,
            'alive' => $resource->alive,
            'cleaned' => $resource->cleaned,
            'messages' => is_array($resource->messages) ? $resource->messages : null,
            'revision-started-at' => $resource->revision_started_at,
            'closed-at' => $resource->closed_at,
            'latest-heartbeat-at' => $resource->latest_heartbeat_at,
            'top-ancestor-created-at' => $resource->top_ancestor_created_at,
            'created-at' => $resource->created_at->toAtomString()
        ];
    }
}
