<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\AlertTriggerShutdown;
use App\Library\Services\AlertTriggerService;
use App\Library\Services\UiPathOrchestratorService;
use Illuminate\Support\Facades\Auth;
use GuzzleHttp\Client as Guzzle;
use GuzzleHttp\Exception\RequestException;
use Carbon\Carbon;

class AppController extends Controller
{

    public function shutdownAlertTriggers(Request $request, AlertTriggerService $service)
    {
        $reason = $request->get('reason');
        if (!$service->isUnderShutdown()) {
            return AlertTriggerShutdown::create([
                'reason' => $reason
            ]);
        }
        return null;
    }

    public function reactivateAlertTriggers(Request $request, AlertTriggerService $service)
    {
        $reason = $request->get('reason');
        if ($service->isUnderShutdown()) {
            $shutdown = $service->currentShutdown();
            if ($shutdown->update([
                'ended_at' => Carbon::now(),
                'ended_reason' => $reason
            ])) {
                return $shutdown;
            };
        }
        return null;
    }

    public function debug(UiPathOrchestratorService $service)
    {
        $guzzle = new Guzzle([
            'base_uri' => 'http://swdcfregb705:9200/'
        ]);

        try {
            $json = '
                {
                    "sort":[
                        {
                            "timestamp":{
                                "order":"desc",
                                "unmapped_type":"boolean"
                            }
                        }
                    ],
                    "query":{
                        "bool":{
                            "must":[
                                {
                                    "query_string":{
                                        "query":"DestCountry: IT",
                                        "analyze_wildcard":true
                                    }
                                }
                            ],
                            "filter":[
                                {
                                    "range":{
                                        "timestamp":{
                                            "format":"strict_date_optional_time",
                                            "gte":"2020-03-30T20:41:23.862Z",
                                            "lte":"2020-03-30T20:56:23.863Z"
                                        }
                                    }
                                }
                            ]
                        }
                    }
                }
            ';
            $response = $guzzle->request('POST', 'kibana_sample_data_flights/_search', [
                'headers' => [
                    'Content-Type' => 'application/json',
                    'Accept' => 'application/json'
                ],
                'body' => $json
            ]);
            return json_decode($response->getBody(), true)['hits']['total']['value'];
        } catch (RequestException $e) {
            return $e;
        }
        return null;
    }
}
