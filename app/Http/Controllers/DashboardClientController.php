<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Client;
use App\WatchedAutomatedProcess;
use App\UiPathRobot;
use App\Alert;

class DashboardClientController extends Controller
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
     * Show the application dashboard for a specific client.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Client $client)
    {
        $pendingAlerts = Alert::whereHas('watchedAutomatedProcess', function($query) use($client) {
            $query->where('client_id', $client->id);
        })->where(function ($query) {
            $query->where('closed', false);
        })->get();
        $underRevisionAlerts = Alert::whereHas('watchedAutomatedProcess', function($query) use($client) {
            $query->where('client_id', $client->id);
        })->where(function ($query) {
            $query->where('under_revision', true);
        })->get();
        $closedAlerts = Alert::whereHas('watchedAutomatedProcess', function($query) use($client) {
            $query->where('client_id', $client->id);
        })->where(function ($query) {
            $query->where('closed', true);
        })->get();
        $clients = Client::all();

        return view('dashboard.client.index', [
            'page' => 'dashboard.client.index.' . $client->id,
            'client' => $client,
            'pendingAlerts' => $pendingAlerts,
            'closedAlerts' => $closedAlerts,
            'clients' => $clients,
            'watchedAutomatedProcessesCount' => WatchedAutomatedProcess::where('client_id', $client->id)->count(),
            'robotsCount' => UiPathRobot::all()->count(),
            'openedAlertsCount' => $pendingAlerts->count(),
            'underRevisionAlertsCount' => $underRevisionAlerts->count(),
            'closedAlertsCount' => $closedAlerts->count()
        ]);
    }

    /**
     * Show the application dashboard as table for a specific client.
     *
     * @return \Illuminate\Http\Response
     */
    public function alertTable(Request $request, Client $client, $closed, $id)
    {
        $alerts = Alert::whereHas('watchedAutomatedProcess', function($query) use($client) {
            $query->where('client_id', $client->id);
        })->where(function ($query) use($closed) {
            $query->where('closed', $closed);
        })->get();
        return view('dashboard.alert.table')
            ->with('alerts', $alerts)
            ->with('tableID', $id)
            ->with('options', [ 'closed' => $closed ]);
    }

    /**
     * Show a client element.
     *
     * @return \Illuminate\Http\Response
     */
    public function element(Request $request, Client $client)
    {
        return view('dashboard.client.element')->with('client', $client);
    }
}
