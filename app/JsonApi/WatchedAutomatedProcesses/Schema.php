<?php

namespace App\JsonApi\WatchedAutomatedProcesses;

use Neomerx\JsonApi\Schema\SchemaProvider;

class Schema extends SchemaProvider
{

    /**
     * @var string
     */
    protected $resourceType = 'watched-automated-processes';

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
            'client-id' => $resource->client_id,
            'code' => $resource->code,
            'name' => $resource->name,
            'operational-handbook-page-url' => $resource->operational_handbook_page_url,
            'kibana-dashboard-url' => $resource->kibana_dashboard_url,
            'additional-information' => $resource->additional_information,
            'running-period-monday' => $resource->running_period_monday,
            'running-period-tuesday' => $resource->running_period_tuesday,
            'running-period-wednesday' => $resource->running_period_wednesday,
            'running-period-thursday' => $resource->running_period_thursday,
            'running-period-friday' => $resource->running_period_friday,
            'running-period-saturday' => $resource->running_period_saturday,
            'running-period-sunday' => $resource->running_period_sunday,
            'running-period-time-from' => $resource->running_period_time_from,
            'running-period-time-until' => $resource->running_period_time_until,
            'created-at' => $resource->created_at->toAtomString()
        ];
    }
}
