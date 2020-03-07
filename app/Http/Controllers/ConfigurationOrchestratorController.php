<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Client;
use App\WatchedAutomatedProcess;
use App\UiPathRobot;
use App\Alert;
use Illuminate\Support\Facades\Auth;

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
        
        return view('configuration.orchestrator.index', [
            'page' => 'configuration.orchestrator.index',
            'alerts' => $alerts,
            'clients' => $clients,
            'clientsCount' => $clients->count(),
            'watchedAutomatedProcessesCount' => WatchedAutomatedProcess::all()->count(),
            'robotsCount' => UiPathRobot::all()->count(),
            'openedAlertsCount' => Alert::where('closed', false)->count(),
            'underRevisionAlertsCount' => Alert::where('under_revision', true)->count(),
            'closedAlertsCount' => Alert::where('closed', true)->count()
        ]);
    }
}