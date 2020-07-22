<?php

namespace App\Library\Services;

use App\Client;
use App\UiPathRobot;
use GuzzleHttp\Client as Guzzle;
use GuzzleHttp\Exception\RequestException;
use Carbon\Carbon;

class ElasticSearchService {

    protected function getGuzzle(Client $client)
    {
        $url = $client->elastic_search_server_url;
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

    public function search(Client $client, $query, $from, $until)
    {
        $result = $this->getDefaultResult();

        $guzzle = $this->getGuzzle($client);
        $headers = $this->getHeaders();
        try {
            $json = $this->getSearchPayload($query, $from, $until);
            $now = Carbon::now();
            $suffix = $now->format('Y.m');
            $result['count'] = json_decode(
                $guzzle->request('POST', "{$client->elastic_search_index}-$suffix/_search", [
                    'headers' => $headers,
                    'body' => $json
                ])->getBody(),
                true
            )['hits']['total']['value'];
        } catch (RequestException $e) {
            $result = $this->getErrorResult("impossible to make searches on $client->elastic_search_server_url (index: $client->elastic_search_index)");
        }

        return $result;
    }
}