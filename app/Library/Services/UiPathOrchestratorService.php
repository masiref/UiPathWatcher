<?php

namespace App\Library\Services;

use App\Client;
use App\UiPathRobot;
use GuzzleHttp\Client as Guzzle;
use GuzzleHttp\Exception\RequestException;

class UiPathOrchestratorService {

    protected function getGuzzle(Client $client)
    {
        $url = $client->orchestrator->url;
        return new Guzzle([
            'base_uri' => "$url"
        ]);
    }

    protected function getHeaders($token)
    {
        return [
            'Authorization' => 'Bearer ' . $token,        
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
    
    public function authenticate(Client $client)
    {
        $result = $this->getDefaultResult();

        $tenant = $client->ui_path_orchestrator_tenant;
        $username = $client->ui_path_orchestrator_api_user_username;
        $password = $client->ui_path_orchestrator_api_user_password;

        $guzzle = $this->getGuzzle($client);
        try {
            $response = $guzzle->request('POST', 'api/account/authenticate', [
                'json' => [
                    'tenancyName' => $tenant,
                    'usernameOrEmailAddress' => $username,
                    'password' => $password
                ]
            ]);
            $token = json_decode($response->getBody(), true)['result'];
            $result['token'] = $token;
        } catch (RequestException $e) {
            $message = $e->getMessage();
            $result = $this->getErrorResult("impossible to authenticate to $client->orchestrator ($tenant tenant) with $username user: $message");
        }

        return $result;
    }

    public function getReleases(Client $client, $token, $filter = '')
    {
        $result = $this->getDefaultResult();

        $guzzle = $this->getGuzzle($client);
        $headers = $this->getHeaders($token);
        try {
            $result['releases'] = json_decode(
                $guzzle->request('GET', 'odata/Releases' . ($filter !== '' ? "?\$filter=$filter" : ''), [
                    'headers' => $headers
                ])->getBody(),
                true
            )['value'];
        } catch (RequestException $e) {
            $message = $e->getMessage();
            $result = $this->getErrorResult("impossible to get releases from $client->orchestrator: $message");
        }

        return $result;
    }

    public function getRobots(Client $client, $token, $filter = '')
    {
        $result = $this->getDefaultResult();

        $guzzle = $this->getGuzzle($client);
        $headers = $this->getHeaders($token);
        try {
            $result['robots'] = json_decode(
                $guzzle->request('GET', 'odata/Robots' . ($filter !== '' ? "?\$filter=$filter" : ''), [
                    'headers' => $headers
                ])->getBody(),
                true
            )['value'];
        } catch (RequestException $e) {
            $message = $e->getMessage();
            $result = $this->getErrorResult("impossible to get robots from $client->orchestrator: $message");
        }

        return $result;
    }

    public function getQueues(Client $client, $token, $filter = '')
    {
        $result = $this->getDefaultResult();

        $guzzle = $this->getGuzzle($client);
        $headers = $this->getHeaders($token);
        try {
            $result['queues'] = json_decode(
                $guzzle->request('GET', 'odata/QueueDefinitions' . ($filter !== '' ? "?\$filter=$filter" : ''), [
                    'headers' => $headers
                ])->getBody(),
                true
            )['value'];
        } catch (RequestException $e) {
            $message = $e->getMessage();
            $result = $this->getErrorResult("impossible to get queues from $client->orchestrator: $message");
        }

        return $result;
    }

    public function getQueueItems(Client $client, $token, $filter = '')
    {
        $result = $this->getDefaultResult();

        $guzzle = $this->getGuzzle($client);
        $headers = $this->getHeaders($token);
        try {
            $result['queue-items'] = json_decode(
                $guzzle->request('GET', 'odata/QueueItems' . ($filter !== '' ? "?\$filter=$filter" : ''), [
                    'headers' => $headers
                ])->getBody(),
                true
            )['value'];
        } catch (RequestException $e) {
            $message = $e->getMessage();
            $result = $this->getErrorResult("impossible to get queue items from $client->orchestrator: $message");
        }

        return $result;
    }

    public function getJobs(Client $client, $token, $filter = '')
    {
        $result = $this->getDefaultResult();

        $guzzle = $this->getGuzzle($client);
        $headers = $this->getHeaders($token);

        try {
            $result['jobs'] = json_decode(
                $guzzle->request('GET', 'odata/Jobs' . ($filter !== '' ? "?\$filter=$filter" : ''), [
                    'headers' => $headers
                ])->getBody(),
                true
            )['value'];
        } catch (RequestException $e) {
            $message = $e->getMessage();
            $result = $this->getErrorResult("impossible to get jobs from $client->orchestrator: $message");
        }

        return $result;
    }

    public function getSession(UiPathRobot $robot, $token)
    {
        $result = $this->getDefaultResult();

        $client = Client::all()->where('orchestrator', $robot->orchestrator)->first();
        $guzzle = $this->getGuzzle($client);
        $headers = $this->getHeaders($token);
        try {
            $result['session'] = json_decode(
                $guzzle->request('GET', "odata/Sessions?\$filter=Robot/Id%20eq%20$robot->external_id", [
                    'headers' => $headers
                ])->getBody(),
                true
            )['value'][0];
        } catch (\Exception $e) {
            $message = $e->getMessage();
            $result = $this->getErrorResult("impossible to get state for $robot from $robot->orchestrator: $message");
        }

        return $result;
    }
}