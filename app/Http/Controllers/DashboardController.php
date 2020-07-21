<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Client;
use App\WatchedAutomatedProcess;
use App\UiPathOrchestrator;
use App\UiPathRobot;
use App\Alert;
use App\AlertTrigger;
use App\AlertCategory;
use App\Library\Services\UiPathOrchestratorService;
use Illuminate\Support\Facades\Auth;
use GuzzleHttp\Client as Guzzle;
use GuzzleHttp\Exception\RequestException;

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
        $closedAlerts = Alert::all()->where('closed', true)->where('parent', null);
        $clients = Client::all();

        return view('dashboard.index', [
            'page' => 'dashboard.index',
            'orchestratorsCount' => UiPathOrchestrator::all()->count(),
            'pendingAlerts' => $pendingAlerts,
            'closedAlerts' => $closedAlerts,
            'clients' => $clients,
            'clientsCount' => $clients->count(),
            'watchedAutomatedProcessesCount' => WatchedAutomatedProcess::all()->count(),
            'robotsCount' => UiPathRobot::all()->count(),
            'alertTriggersCount' => AlertTrigger::all()->where('deleted', false)->count(),
            'openedAlertsCount' => Alert::where('closed', false)->count(),
            'underRevisionAlertsCount' => Alert::where('under_revision', true)->count(),
            'closedAlertsCount' => $closedAlerts->count()
        ]);
    }

    /**
     * Show information tiles.
     *
     * @return \Illuminate\Http\Response
     */
    public function tiles(Request $request, Client $client = null)
    {
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
                if ($client) {
                    $value = WatchedAutomatedProcess::where('client_id', $client->id)->count();
                } else {
                    $value = WatchedAutomatedProcess::all()->count();
                }
                $parameter = 'watchedAutomatedProcessesCount';
                break;

                case 'robots':
                if ($client) {
                    $value = UiPathRobot::where('ui_path_orchestrator_id', $client->orchestrator->id)->count();
                } else {
                    $value = UiPathRobot::all()->count();
                }
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
                $value = $alerts->where('closed', true)->where('parent', null)->count();
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
     * Show an alert timeline.
     *
     * @return \Illuminate\Http\Response
     */
    public function alertTimeline(Request $request, Alert $alert)
    {
        $ancestors = array();
        $ancestor = Alert::all()->where('parent_id', $alert->id)->first();
        while ($ancestor !== null) {
            array_push($ancestors, $ancestor);
            $ancestor = Alert::all()->where('parent_id', $ancestor->id)->first();
        }
        return view('dashboard.alert.timeline')->with('alert', $alert)->with('ancestors', $ancestors);
    }

    /**
     * Show the application dashboard as table.
     *
     * @return \Illuminate\Http\Response
     */
    public function alertTable(Request $request, $closed, $id)
    {
        $alerts = Alert::all()->where('closed', $closed);
        if ($closed) {
            $alerts = $alerts->where('parent', null);
        }
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
     * Show alert closing form modal.
     *
     * @return \Illuminate\Http\Response
     */
    public function alertClosingFormModal(Request $request, Alert $alert)
    {
        $categories = AlertCategory::all();
        return view('dashboard.alert.forms.closing')->with('alert', $alert)->with('categories', $categories);
    }

    /**
     * Show alert ignorance form modal.
     *
     * @return \Illuminate\Http\Response
     */
    public function alertIgnoranceFormModal(Request $request, Alert $alert)
    {
        $categories = AlertCategory::all();
        return view('dashboard.alert.forms.ignorance')->with('alert', $alert)->with('categories', $categories);
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

    /**
     * Show quick board.
     *
     * @return \Illuminate\Http\Response
     */
    public function quickBoard(Request $request, Client $client = null)
    {
        if ($client) {
            return view('dashboard.client.quick-board', [
                'client' => $client
            ]);
        }
        return view('dashboard.quick-board', [
            'clients' => Client::all()
        ]);
    }

    public function debug(UiPathOrchestratorService $service)
    {
        $guzzle = new Guzzle([
            'base_uri' => 'http://swdcfregb705:9200/'
        ]);

        try {
            $json = '
                {
                    "sort":[
                        {
                            "timestamp":{
                                "order":"desc",
                                "unmapped_type":"boolean"
                            }
                        }
                    ],
                    "query":{
                        "bool":{
                            "must":[
                                {
                                    "query_string":{
                                        "query":"DestCountry: IT",
                                        "analyze_wildcard":true
                                    }
                                }
                            ],
                            "filter":[
                                {
                                    "range":{
                                        "timestamp":{
                                            "format":"strict_date_optional_time",
                                            "gte":"2020-03-30T20:41:23.862Z",
                                            "lte":"2020-03-30T20:56:23.863Z"
                                        }
                                    }
                                }
                            ]
                        }
                    }
                }
            ';
            $response = $guzzle->request('POST', 'kibana_sample_data_flights/_search', [
                'headers' => [
                    'Content-Type' => 'application/json',
                    'Accept' => 'application/json'
                ],
                'body' => $json
            ]);
            return json_decode($response->getBody(), true)['hits']['total']['value'];
        } catch (RequestException $e) {
            return $e;
        }
        return null;
    }
}
