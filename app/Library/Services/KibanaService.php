<?php

namespace App\Library\Services;

use App\UiPathOrchestrator;
use App\UiPathRobot;
use GuzzleHttp\Client as Guzzle;
use GuzzleHttp\Exception\RequestException;

class KibanaService {

    protected function getGuzzle(UiPathOrchestrator $orchestrator)
    {
        $url = $orchestrator->elastic_search_server_url;
        return new Guzzle([
            'base_uri' => "$url"
        ]);
    }

    protected function getHeaders()
    {
        return [
            'Content-Type'  => 'application/json',       
            'Accept'        => 'application/json',
        ];
    }

    protected function getDefaultResult()
    {
        return [
            'error'        => false,
            'errorMessage' => ''
        ];
    }

    protected function getErrorResult($message)
    {
        return [
            'error'        => true,
            'errorMessage' => $message
        ];
    }

    protected function getSearchPayload($query, $from, $until)
    {
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
                                    "query":"' . $query .'",
                                    "analyze_wildcard":true
                                }
                            }
                        ],
                        "filter":[
                            {
                                "range":{
                                    "timestamp":{
                                        "format":"basic_date_time_no_millis",
                                        "gte":"' . $from->toDateTimeLocalString() .'",
                                        "lte":"' . $until->toDateTimeLocalString() .'"
                                    }
                                }
                            }
                        ]
                    }
                }
            }
        ';
        return $json;
    }

    public function search(UiPathOrchestrator $orchestrator, $query, $from, $until)
    {
        $result = $this->getDefaultResult();

        $guzzle = $this->getGuzzle($orchestrator);
        $headers = $this->getHeaders();
        try {
            $result['count'] = json_decode(
                $guzzle->request('POST', "{$orchestrator->elastic_search_index}/_search", [
                    'headers' => $headers,
                    'body' => $json
                ])->getBody(),
                true
            )['hits']['total']['value'];
        } catch (RequestException $e) {
            $result = $this->getErrorResult("impossible to make searches on $orchestrator->elastic_search_server_url (index: $orchestrator->elastic_search_index)");
        }

        return $result;
    }
}