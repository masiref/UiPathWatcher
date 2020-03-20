<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Client;
use App\WatchedAutomatedProcess;
use App\UiPathOrchestrator;
use App\UiPathRobot;
use App\Alert;
use Illuminate\Support\Facades\Auth;

class ConfigurationClientController extends Controller
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
     * Show the client configuration page.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $alerts = Alert::all()->where('closed', false);
        $clients = Client::all();
        
        return view('configuration.client.index', [
            'page' => 'configuration.client.index',
            'alerts' => $alerts,
            'clients' => $clients,
            'orchestrators' => UiPathOrchestrator::all(),
            'clientsCount' => $clients->count(),
            'watchedAutomatedProcessesCount' => WatchedAutomatedProcess::all()->count(),
            'robotsCount' => UiPathRobot::all()->count(),
            'openedAlertsCount' => Alert::where('closed', false)->count(),
            'underRevisionAlertsCount' => Alert::where('under_revision', true)->count(),
            'closedAlertsCount' => Alert::where('closed', true)->count(),
            'orchestratorsCount' => UiPathOrchestrator::all()->count()
        ]);
    }

    /**
     * Show the clients as table.
     *
     * @return \Illuminate\Http\Response
     */
    public function table(Request $request)
    {
        $clients = Client::all();
        return view('configuration.client.table')
            ->with('clients', $clients);
    }
}