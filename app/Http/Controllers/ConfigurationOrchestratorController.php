<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Client;
use App\WatchedAutomatedProcess;
use App\UiPathOrchestrator;
use App\UiPathRobot;
use App\Alert;
use App\AlertTrigger;
use App\Library\Services\UiPathOrchestratorService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use GuzzleHttp\Client as Guzzle;

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
            'orchestrators' => $orchestrators->sortBy('name'),
            'clientsCount' => $clients->count(),
            'watchedAutomatedProcessesCount' => WatchedAutomatedProcess::all()->count(),
            'robotsCount' => UiPathRobot::all()->count(),
            'alertTriggersCount' => AlertTrigger::all()->where('deleted', false)->count(),
            'openedAlertsCount' => Alert::where('closed', false)->count(),
            'underRevisionAlertsCount' => Alert::where('under_revision', true)->count(),
            'closedAlertsCount' => Alert::where('closed', true)->count(),
            'orchestratorsCount' => $orchestrators->count()
        ]);
    }

    /**
     * Show the edit form.
     *
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request, UiPathOrchestrator $orchestrator)
    {
        return view('configuration.orchestrator.form.edit')
            ->with('orchestrator', $orchestrator);
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

    /**
     * Get processes from UiPath Orchestrator API.
     *
     * @return \Illuminate\Http\Response
     */
    public function processes(Request $request, Client $client, UiPathOrchestratorService $orchestratorService)
    {
        $result = $orchestratorService->authenticate($client);
        if ($result['error']) {
            return ['error' => $result['errorMessage']];
        } else {
            $token = $result['token'];
            $result = $orchestratorService->getReleases($client, $token);
            if ($result['error']) {
                return ['error' => $result['errorMessage']];
            } else {
                return $result['releases'];
            }
        }
    }

    /**
     * Get robots from UiPath Orchestrator API.
     *
     * @return \Illuminate\Http\Response
     */
    public function robots(Request $request, Client $client, UiPathOrchestratorService $orchestratorService)
    {
        $result = $orchestratorService->authenticate($client);
        if ($result['error']) {
            return ['error' => $result['errorMessage']];
        } else {
            $token = $result['token'];
            $result = $orchestratorService->getRobots($client, $token);
            if ($result['error']) {
                return ['error' => $result['errorMessage']];
            } else {
                return $result['robots'];
            }
        }
    }

    /**
     * Get queues from UiPath Orchestrator API.
     *
     * @return \Illuminate\Http\Response
     */
    public function queues(Request $request, Client $client, UiPathOrchestratorService $orchestratorService)
    {
        $result = $orchestratorService->authenticate($client);
        if ($result['error']) {
            return ['error' => $result['errorMessage']];
        } else {
            $token = $result['token'];
            $result = $orchestratorService->getQueues($client, $token);
            if ($result['error']) {
                return ['error' => $result['errorMessage']];
            } else {
                return $result['queues'];
            }
        }
    }
}