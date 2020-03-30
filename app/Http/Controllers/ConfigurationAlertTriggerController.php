<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Client;
use App\WatchedAutomatedProcess;
use App\UiPathOrchestrator;
use App\UiPathRobot;
use App\Alert;
use App\AlertTrigger;
use App\AlertTriggerDefinition;
use App\AlertTriggerRule;
use Illuminate\Support\Facades\Auth;

class ConfigurationAlertTriggerController extends Controller
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
     * Show the alert trigger configuration page.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $alerts = Alert::all()->where('closed', false);
        $clients = Client::all();
        $watchedAutomatedProcesses = WatchedAutomatedProcess::all();
        
        return view('configuration.alert-trigger.index', [
            'page' => 'configuration.alert-trigger.index',
            'alerts' => $alerts,
            'clients' => $clients->sortBy('name'),
            'clientsCount' => $clients->count(),
            'watchedAutomatedProcessesCount' => $watchedAutomatedProcesses->count(),
            'robotsCount' => UiPathRobot::all()->count(),
            'alertTriggersCount' => AlertTrigger::all()->count(),
            'openedAlertsCount' => Alert::where('closed', false)->count(),
            'underRevisionAlertsCount' => Alert::where('under_revision', true)->count(),
            'closedAlertsCount' => Alert::where('closed', true)->count(),
            'orchestratorsCount' => UiPathOrchestrator::all()->count()
        ]);
    }

    /**
     * Show default alert trigger details (for creation).
     *
     * @return \Illuminate\Http\Response
     */
    public function defaultAlertTriggerDetails(WatchedAutomatedProcess $watchedAutomatedProcess, $title = '')
    {
        $alertTrigger = new AlertTrigger([
            'title' => $title,
            'watched_automated_process_id' => $watchedAutomatedProcess->id
        ]);

        return view('configuration.alert-trigger.steps.content.trigger-details.index', [
            'alertTrigger' => $alertTrigger
        ]);
    }

    /**
     * Show default alert definition (for creation).
     *
     * @return \Illuminate\Http\Response
     */
    public function defaultAlertTriggerDefinition($rank)
    {
        $alertTriggerDefinition = new AlertTriggerDefinition([
            'rank' => $rank,
            'level' => 'info'
        ]);
        return view("configuration.alert-trigger.steps.content.trigger-details.alert-definition.element", [
            'alertTriggerDefinition' => $alertTriggerDefinition
        ]);
    }

    /**
     * Show default alert rule (for creation).
     *
     * @return \Illuminate\Http\Response
     */
    public function defaultAlertTriggerRule(WatchedAutomatedProcess $watchedAutomatedProcess, $rank, $type)
    {
        $alertTriggerRule = new AlertTriggerRule([
            'rank' => $rank,
            'type' => $type
        ]);
        return view("configuration.alert-trigger.steps.content.trigger-details.alert-definition.rule.element", [
            'alertTriggerRule' => $alertTriggerRule,
            'watchedAutomatedProcess' => $watchedAutomatedProcess
        ]);
    }
}