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
use Illuminate\Support\Facades\Auth;

class ConfigurationRobotToolController extends Controller
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
        $robotTools = UiPathRobotTool::all();
        $clients = Client::all();
        $orchestrators = UiPathOrchestrator::all();
        
        return view('configuration.robot-tool.index', [
            'page' => 'configuration.robot-tool.index',
            'robotTools' => $robotTools,
            'clients' => $clients,
            'clientsCount' => $clients->count(),
            'watchedAutomatedProcessesCount' => WatchedAutomatedProcess::all()->count(),
            'robotsCount' => UiPathRobot::all()->count(),
            'alertTriggersCount' => AlertTrigger::all()->where('deleted', false)->count(),
            'openedAlertsCount' => Alert::where('closed', false)->count(),
            'underRevisionAlertsCount' => Alert::where('under_revision', true)->count(),
            'closedAlertsCount' => Alert::where('closed', true)->count(),
            'orchestratorsCount' => $orchestrators->count(),
            'robotToolsCount' => $robotTools->count()
        ]);
    }

    /**
     * Show the edit form.
     *
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request, UiPathRobotTool $robotTool)
    {
        return view('configuration.robot-tool.form.edit')
            ->with('robotTool', $robotTool);
    }

    /**
     * Show the robot tools as table.
     *
     * @return \Illuminate\Http\Response
     */
    public function table(Request $request)
    {
        $robotTools = UiPathRobotTool::all();
        return view('configuration.robot-tool.table')
            ->with('robotTools', $robotTools);
    }
}