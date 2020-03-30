<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Alert;
use App\Client;
use App\UiPathOrchestrator;
use App\WatchedAutomatedProcess;
use App\AlertTrigger;
use Illuminate\Support\Facades\Auth;

class DashboardUserController extends Controller
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
        $alerts = Alert::all()->where('reviewer_id', Auth::user()->id);
        $pendingAlerts = $alerts->where('closed', false);
        $closedAlerts = $alerts->where('closed', true);
        $clients = Client::all();

        return view('dashboard.user.index', [
            'page' => 'dashboard.user.index',
            'pendingAlerts' => $pendingAlerts,
            'closedAlerts' => $closedAlerts,
            'orchestratorsCount' => UiPathOrchestrator::all()->count(),
            'clients' => $clients,
            'clientsCount' => $clients->count(),
            'watchedAutomatedProcessesCount' => WatchedAutomatedProcess::all()->count(),
            'alertTriggersCount' => AlertTrigger::all()->count(),
            'openedAlertsCount' => $pendingAlerts->count(),
            'underRevisionAlertsCount' => $pendingAlerts->where('under_revision', true)->count(),
            'closedAlertsCount' => $closedAlerts->count()
        ]);
    }

    public function tiles(Request $request) {
        $tiles = [ 'alerts-not-closed', 'alerts-under-revision', 'alerts-closed' ];

        $count;
        $parameter;
        $alerts = Alert::all()->where('reviewer_id', Auth::user()->id);
        
        $result = array();
        foreach ($tiles as $tile) {
            switch ($tile) {
                case 'alerts-not-closed':
                $count = $alerts->where('closed', false)->count();
                $parameter = 'openedAlertsCount';
                break;

                case 'alerts-under-revision':
                $count = $alerts->where('under_revision', true)->count();
                $parameter = 'underRevisionAlertsCount';
                break;

                case 'alerts-closed':
                $count = $alerts->where('closed', true)->count();
                $parameter = 'closedAlertsCount';
                break;
            }
            $result[ $tile ] = view("dashboard.user.tiles.$tile")->with($parameter, $count)->render();
        }
        return $result;
    }

    /**
     * Show the application dashboard as table.
     *
     * @return \Illuminate\Http\Response
     */
    public function alertTable(Request $request, $closed, $id) {
        $user = Auth::user();
        $alerts = Alert::whereHas('watchedAutomatedProcess', function($query) use($user, $closed) {
            $query->where('reviewer_id', $user->id)->where('closed', $closed);
        })->get();
        return view('dashboard.alert.table')
            ->with('alerts', $alerts)
            ->with('tableID', $id)
            ->with('options', [ 'closed' => $closed ]);
    }
}
