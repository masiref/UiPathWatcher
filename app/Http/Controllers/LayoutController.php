<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Client;
use App\UiPathOrchestrator;
use App\WatchedAutomatedProcess;
use App\AlertTrigger;
use App\UiPathRobotTool;
use Illuminate\Support\Facades\Auth;

class LayoutController extends Controller
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
     * Show the menu.
     *
     * @return \Illuminate\Http\Response
     */
    public function menu(Request $request, $page = 'dashboard.index')
    {
        $clients = Client::all();
        $robotTools = UiPathRobotTool::all();

        return view('layouts.menu', [
            'clients' => $clients,
            'clientsCount' => $clients->count(),
            'orchestratorsCount' => UiPathOrchestrator::all()->count(),
            'watchedAutomatedProcessesCount' => WatchedAutomatedProcess::all()->count(),
            'alertTriggersCount' => AlertTrigger::all()->where('deleted', false)->count(),
            'robotToolsCount' => $robotTools->count(),
            'page' => $page
        ]);
    }

    /**
     * Show the sidebar.
     *
     * @return \Illuminate\Http\Response
     */
    public function sidebar(Request $request, $page = 'dashboard.index')
    {
        $clients = Client::all();
        $robotTools = UiPathRobotTool::all();

        return view('layouts.sidebar', [
            'clients' => $clients,
            'clientsCount' => $clients->count(),
            'orchestratorsCount' => UiPathOrchestrator::all()->count(),
            'watchedAutomatedProcessesCount' => WatchedAutomatedProcess::all()->count(),
            'alertTriggersCount' => AlertTrigger::all()->where('deleted', false)->count(),
            'robotToolsCount' => $robotTools->count(),
            'page' => $page
        ]);
    }

    /**
     * Show the hero.
     *
     * @return \Illuminate\Http\Response
     */
    public function hero(Request $request)
    {
        return view('layouts.hero');
    }
}