<?php

namespace App\Library\Services;

use App\UiPathOrchestrator;
use App\UiPathRobot;
use GuzzleHttp\Client as Guzzle;
use GuzzleHttp\Exception\RequestException;

class UiPathOrchestratorService {

    protected function getGuzzle(UiPathOrchestrator $orchestrator)
    {
        $url = $orchestrator->url;
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
    
    public function authenticate(UiPathOrchestrator $orchestrator)
    {
        $result = $this->getDefaultResult();

        $tenant = $orchestrator->tenant;
        $username = $orchestrator->api_user_username;
        $password = $orchestrator->api_user_password;

        $guzzle = $this->getGuzzle($orchestrator);
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
            $result = $this->getErrorResult("impossible to authenticate to $orchestrator->url ($tenant tenant) with $username user.");
        }

        return $result;
    }

    public function getReleases(UiPathOrchestrator $orchestrator, $token, $filter = '')
    {
        $result = $this->getDefaultResult();

        $guzzle = $this->getGuzzle($orchestrator);
        $headers = $this->getHeaders($token);
        try {
            $result['releases'] = json_decode(
                $guzzle->request('GET', 'odata/Releases' . ($filter !== '' ? "?\$filter=$filter" : ''), [
                    'headers' => $headers
                ])->getBody(),
                true
            )['value'];
        } catch (RequestException $e) {
            $result = $this->getErrorResult("impossible to get releases from $orchestrator->url");
        }

        return $result;
    }

    public function getRobots(UiPathOrchestrator $orchestrator, $token, $filter = '')
    {
        $result = $this->getDefaultResult();

        $guzzle = $this->getGuzzle($orchestrator);
        $headers = $this->getHeaders($token);
        try {
            $result['robots'] = json_decode(
                $guzzle->request('GET', 'odata/Robots' . ($filter !== '' ? "?\$filter=$filter" : ''), [
                    'headers' => $headers
                ])->getBody(),
                true
            )['value'];
        } catch (RequestException $e) {
            $result = $this->getErrorResult("impossible to get robots from $orchestrator->url");
        }

        return $result;
    }

    public function getQueues(UiPathOrchestrator $orchestrator, $token, $filter = '')
    {
        $result = $this->getDefaultResult();

        $guzzle = $this->getGuzzle($orchestrator);
        $headers = $this->getHeaders($token);
        try {
            $result['queues'] = json_decode(
                $guzzle->request('GET', 'odata/QueueDefinitions' . ($filter !== '' ? "?\$filter=$filter" : ''), [
                    'headers' => $headers
                ])->getBody(),
                true
            )['value'];
        } catch (RequestException $e) {
            $result = $this->getErrorResult("impossible to get queues from $orchestrator->url");
        }

        return $result;
    }

    public function getQueueItems(UiPathOrchestrator $orchestrator, $token, $filter = '')
    {
        $result = $this->getDefaultResult();

        $guzzle = $this->getGuzzle($orchestrator);
        $headers = $this->getHeaders($token);
        try {
            $result['queue-items'] = json_decode(
                $guzzle->request('GET', 'odata/QueueItems' . ($filter !== '' ? "?\$filter=$filter" : ''), [
                    'headers' => $headers
                ])->getBody(),
                true
            )['value'];
        } catch (RequestException $e) {
            $result = $this->getErrorResult("impossible to get queue items from $orchestrator->url");
        }

        return $result;
    }

    public function getJobs(UiPathOrchestrator $orchestrator, $token, $filter = '')
    {
        $result = $this->getDefaultResult();

        $guzzle = $this->getGuzzle($orchestrator);
        $headers = $this->getHeaders($token);

        try {
            $result['jobs'] = json_decode(
                $guzzle->request('GET', 'odata/Jobs' . ($filter !== '' ? "?\$filter=$filter" : ''), [
                    'headers' => $headers
                ])->getBody(),
                true
            )['value'];
        } catch (RequestException $e) {
            $result = $this->getErrorResult("impossible to get jobs from $orchestrator->url");
        }

        return $result;
    }

    public function getSession(UiPathRobot $robot, $token)
    {
        $result = $this->getDefaultResult();

        $orchestrator = $robot->orchestrator;
        $guzzle = $this->getGuzzle($orchestrator);
        $headers = $this->getHeaders($token);
        try {
            $result['session'] = json_decode(
                $guzzle->request('GET', "odata/Sessions?\$filter=Robot/Id%20eq%20$robot->external_id", [
                    'headers' => $headers
                ])->getBody(),
                true
            )['value'][0];
        } catch (RequestException $e) {
            $result = $this->getErrorResult("impossible to get state for $robot from $orchestrator->url");
        }

        return $result;
    }
}