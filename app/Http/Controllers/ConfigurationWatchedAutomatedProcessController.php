<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Client;
use App\WatchedAutomatedProcess;
use App\UiPathOrchestrator;
use App\UiPathRobot;
use App\Alert;
use Illuminate\Support\Facades\Auth;

class ConfigurationWatchedAutomatedProcessController extends Controller
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
     * Show the watched automated process configuration page.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $alerts = Alert::all()->where('closed', false);
        $clients = Client::all();
        $watchedAutomatedProcesses = WatchedAutomatedProcess::all();
        
        return view('configuration.watched-automated-process.index', [
            'page' => 'configuration.watched-automated-process.index',
            'alerts' => $alerts,
            'clients' => $clients,
            'clientsCount' => $clients->count(),
            'orchestratorsCount' => UiPathOrchestrator::all()->count(),
            'watchedAutomatedProcessesCount' => $watchedAutomatedProcesses->count(),
            'watchedAutomatedProcesses' => $watchedAutomatedProcesses,
            'robotsCount' => UiPathRobot::all()->count(),
            'openedAlertsCount' => Alert::where('closed', false)->count(),
            'underRevisionAlertsCount' => Alert::where('under_revision', true)->count(),
            'closedAlertsCount' => Alert::where('closed', true)->count()
        ]);
    }

    /**
     * Show the watched automated processes as table.
     *
     * @return \Illuminate\Http\Response
     */
    public function table(Request $request)
    {
        $watchedAutomatedProcesses = WatchedAutomatedProcess::all();
        return view('configuration.watched-automated-process.table')
            ->with('watchedAutomatedProcesses', $watchedAutomatedProcesses);
    }
}