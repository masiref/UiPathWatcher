<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Client;
use App\WatchedAutomatedProcess;
use App\UiPathOrchestrator;
use App\UiPathRobot;
use App\Alert;
use App\AlertTrigger;
use App\UiPathRobotTool;

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
            $query->where('closed', false)->where('under_revision', true);
        })->get();
        $closedAlerts = Alert::whereHas('watchedAutomatedProcess', function($query) use($client) {
            $query->where('client_id', $client->id);
        })->where(function ($query) {
            $query->where('closed', true)->where('parent_id', null)->orderByDesc('created_at');
        })->get();
        $robots = UiPathRobot::whereHas('watchedAutomatedProcesses', function($query) use ($client) {
            $query->where('client_id', $client->id);
        })->get();
        $clients = Client::all();
        $robotTools = UiPathRobotTool::all();

        return view('dashboard.client.index', [
            'page' => 'dashboard.client.index.' . $client->id,
            'client' => $client,
            'pendingAlerts' => $pendingAlerts,
            'closedAlerts' => $closedAlerts->take(env('APP_CLOSED_ALERTS_LIMIT', 100)),
            'orchestratorsCount' => UiPathOrchestrator::all()->count(),
            'clients' => $clients,
            'clientsCount' => $clients->count(),
            'watchedAutomatedProcessesCount' => WatchedAutomatedProcess::all()->count(),
            'clientWatchedAutomatedProcessesCount' => WatchedAutomatedProcess::where('client_id', $client->id)->count(),
            'robotsCount' => $robots->count(),
            'alertTriggersCount' => AlertTrigger::all()->where('deleted', false)->count(),
            'clientAlertTriggersCount' => AlertTrigger::whereHas('watchedAutomatedProcess', function($query) use($client) {
                $query->where('client_id', $client->id);
            })->where('deleted', false)->count(),
            'openedAlertsCount' => $pendingAlerts->count(),
            'underRevisionAlertsCount' => $underRevisionAlerts->count(),
            'closedAlertsCount' => $closedAlerts->count(),
            'robotToolsCount' => $robotTools->count()
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
            if ($closed) {
                $query->where('parent_id', null)->orderByDesc('created_at')->take(env('APP_CLOSED_ALERTS_LIMIT', 100));
            }
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
    public function element(Request $request, Client $client, $collapsed = true)
    {
        return view('dashboard.client.element')->with('client', $client)->with('collapsed', $collapsed);
    }

    /**
     * Show all client elements.
     *
     * @return \Illuminate\Http\Response
     */
    public function elements(Request $request)
    {
        $clients = Client::all();
        $result = array();
        foreach ($clients as $client) {
            $result[$client->id] = view('dashboard.client.element')->with('client', $client)->render();
        }
        return $result;
    }

    /**
     * Show a client watched automated process elements.
     *
     * @return \Illuminate\Http\Response
     */
    public function watchedAutomatedProcessElements(Request $request, Client $client, $autonomous)
    {
        $waps = WatchedAutomatedProcess::all()->where('client_id', $client->id);
        $result = array();
        foreach ($waps as $wap) {
            $result[$wap->id] = view('dashboard.watched-automated-process.element')
                ->with('watchedAutomatedProcess', $wap)
                ->with('autonomous', $autonomous)->render();
        }
        return $result;
    }
}
