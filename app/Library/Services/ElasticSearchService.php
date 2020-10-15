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
        $url = $client->elastic_search_url;
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

    protected function getAuth(Client $client)
    {
        if ($client->elastic_search_api_user_username !== '') {
            return [
                $client->elastic_search_api_user_username,
                $client->elastic_search_api_user_password
            ];
        }
        return null;
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
        $from->tz('UTC');
        $until->tz('UTC');
        $json = '
            {
                "track_total_hits": true,
                "query":{
                    "bool":{
                        "must":[
                            {
                                "query_string":{
                                    "query": ' . json_encode($query) .',
                                    "analyze_wildcard": true
                                }
                            }
                        ],
                        "filter":[
                            {
                                "range":{
                                    "@timestamp":{
                                        "format": "date_hour_minute_second",
                                        "gte": "' . $from->toDateTimeLocalString() .'",
                                        "lte": "' . $until->toDateTimeLocalString() .'"
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
        $auth = $this->getAuth($client);

        try {
            $json = $this->getSearchPayload($query, $from, $until);
            $now = Carbon::now();
            $suffix = $now->format('Y.m');
            if ($auth) {
                $result['count'] = json_decode(
                    $guzzle->request('POST', "{$client->elastic_search_index}-$suffix/_search", [
                        'headers' => $headers,
                        'body' => $json,
                        'auth' => $auth
                    ])->getBody(),
                    true
                )['hits']['total']['value'];
            } else {
                $result['count'] = json_decode(
                    $guzzle->request('POST', "{$client->elastic_search_index}-$suffix/_search", [
                        'headers' => $headers,
                        'body' => $json
                    ])->getBody(),
                    true
                )['hits']['total']['value'];
            }
        } catch (RequestException $e) {
            $message = $e->getMessage();
            $result = $this->getErrorResult("impossible to make searches on $client->elastic_search_url (index: $client->elastic_search_index): $message");
        }

        return $result;
    }
}