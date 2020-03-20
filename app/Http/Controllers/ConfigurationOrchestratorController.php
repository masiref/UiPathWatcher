<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Client;
use App\WatchedAutomatedProcess;
use App\UiPathOrchestrator;
use App\UiPathRobot;
use App\Alert;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use GuzzleHttp\Client as Guzzle;
use GuzzleHttp\Exception\RequestException;

class ConfigurationOrchestratorController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the orchestrator configuration page.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $alerts = Alert::all()->where('closed', false);
        $clients = Client::all();
        $orchestrators = UiPathOrchestrator::all();
        
        return view('configuration.orchestrator.index', [
            'page' => 'configuration.orchestrator.index',
            'alerts' => $alerts,
            'clients' => $clients,
            'orchestrators' => $orchestrators,
            'clientsCount' => $clients->count(),
            'watchedAutomatedProcessesCount' => WatchedAutomatedProcess::all()->count(),
            'robotsCount' => UiPathRobot::all()->count(),
            'openedAlertsCount' => Alert::where('closed', false)->count(),
            'underRevisionAlertsCount' => Alert::where('under_revision', true)->count(),
            'closedAlertsCount' => Alert::where('closed', true)->count(),
            'orchestratorsCount' => $orchestrators->count()
        ]);
    }

    /**
     * Show the orchestrators as table.
     *
     * @return \Illuminate\Http\Response
     */
    public function table(Request $request)
    {
        $orchestrators = UiPathOrchestrator::all();
        return view('configuration.orchestrator.table')
            ->with('orchestrators', $orchestrators);
    }

    public function processes(Request $request, Client $client)
    {
        $orchestrator = $client->orchestrator;
        $url = $orchestrator->url;
        $tenant = $orchestrator->tenant;
        $username = $orchestrator->api_user_username;
        $password = $orchestrator->api_user_password;
        
        $guzzle = new Guzzle([
            'base_uri' => "$url"
        ]);
        $result = array();
        try {
            $response = $guzzle->request('POST', 'api/account/authenticate', [
                'json' => [
                    'tenancyName' => $tenant,
                    'usernameOrEmailAddress' => $username,
                    'password' => $password
                ]
            ]);
            $token = json_decode($response->getBody(), true)['result'];
            $headers = [
                'Authorization' => 'Bearer ' . $token,        
                'Accept'        => 'application/json',
            ];
            $environments = json_decode(
                $guzzle->request('GET', 'odata/Environments', [
                    'headers' => $headers
                ])->getBody(),
                true
            )['value'];
            foreach ($environments as $env) {
                $id = $env['Id'];
                $releases = json_decode(
                    $guzzle->request('GET', "odata/Releases?\$filter=EnvironmentId%20eq%20$id", [
                        'headers' => $headers
                    ])->getBody(),
                    true
                )['value'];
                $result = array_merge($result, $releases);
            }
        } catch (RequestException $e) {
            return [ 'error' => 'Impossible to connect to orchestrator' ];
        }
        return $result;
    }
}