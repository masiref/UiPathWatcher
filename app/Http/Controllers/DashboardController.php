<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Client;
use App\WatchedAutomatedProcess;
use App\UiPathOrchestrator;
use App\UiPathRobot;
use App\Alert;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
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
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $pendingAlerts = Alert::all()->where('closed', false);
        $closedAlerts = Alert::all()->where('closed', true);
        $clients = Client::all();

        return view('dashboard.index', [
            'page' => 'dashboard.index',
            'pendingAlerts' => $pendingAlerts,
            'closedAlerts' => $closedAlerts,
            'clients' => $clients,
            'clientsCount' => $clients->count(),
            'watchedAutomatedProcessesCount' => WatchedAutomatedProcess::all()->count(),
            'robotsCount' => UiPathRobot::all()->count(),
            'openedAlertsCount' => Alert::where('closed', false)->count(),
            'underRevisionAlertsCount' => Alert::where('under_revision', true)->count(),
            'closedAlertsCount' => Alert::where('closed', true)->count(),
            'orchestratorsCount' => UiPathOrchestrator::all()->count()
        ]);
    }

    public function tiles(Request $request, Client $client = null) {
        $tiles = [
            'watched-automated-processes',
            'robots',
            'alerts-not-closed',
            'alerts-under-revision', 
            'alerts-closed'
        ];

        $alerts = Alert::all();
        if ($client) {
            array_push($tiles, 'client');
            $alerts = Alert::whereHas('watchedAutomatedProcess', function($query) use($client) {
                $query->where('client_id', $client->id);
            })->get();
        } else {
            array_push($tiles, 'clients');
        }
        
        $result = array();
        foreach ($tiles as $tile) {
            switch ($tile) {
                case 'client':
                $value = $client;
                $parameter = 'client';
                break;

                case 'clients':
                $value = Client::all()->count();
                $parameter = 'clientsCount';
                break;

                case 'watched-automated-processes':
                $value = WatchedAutomatedProcess::all()->count();
                if ($client) {
                    $value = WatchedAutomatedProcess::where('client_id', $client->id)->count();
                }
                $parameter = 'watchedAutomatedProcessesCount';
                break;

                case 'robots':
                $value = UiPathRobot::all()->count();
                $parameter = 'robotsCount';
                break;

                case 'alerts-not-closed':
                $value = $alerts->where('closed', false)->count();
                $parameter = 'openedAlertsCount';
                break;

                case 'alerts-under-revision':
                $value = $alerts->where('under_revision', true)->count();
                $parameter = 'underRevisionAlertsCount';
                break;

                case 'alerts-closed':
                $value = $alerts->where('closed', true)->count();
                $parameter = 'closedAlertsCount';
                break;
            }
            $result[ $tile ] = view("dashboard.tiles.$tile")->with($parameter, $value)->render();
        }
        return $result;
    }

    /**
     * Show an alert element.
     *
     * @return \Illuminate\Http\Response
     */
    public function alertElement(Request $request, Alert $alert)
    {
        return view('dashboard.alert.element')->with('alert', $alert);
    }

    /**
     * Show the application dashboard as table.
     *
     * @return \Illuminate\Http\Response
     */
    public function alertTable(Request $request, $closed, $id)
    {
        $alerts = Alert::all()->where('closed', $closed);
        return view('dashboard.alert.table')
            ->with('alerts', $alerts)
            ->with('tableID', $id)
            ->with('options', [ 'closed' => $closed ]);
    }

    /**
     * Show an alert element as table row.
     *
     * @return \Illuminate\Http\Response
     */
    public function alertTableRow(Request $request, Alert $alert)
    {
        return view('dashboard.alert.table-row')->with('alert', $alert)->with('options', [ 'closed' => false ]);
    }

    /**
     * Show watched automated process element.
     *
     * @return \Illuminate\Http\Response
     */
    public function watchedAutomatedProcessElement(
        Request $request,
        WatchedAutomatedProcess $watchedAutomatedProcess,
        $autonomous)
    {
        return view('dashboard.watched-automated-process.element')
            ->with('watchedAutomatedProcess', $watchedAutomatedProcess)
            ->with('autonomous', $autonomous);
    }
}
