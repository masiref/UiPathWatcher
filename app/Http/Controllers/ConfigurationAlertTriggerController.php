<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Client;
use App\WatchedAutomatedProcess;
use App\UiPathOrchestrator;
use App\UiPathRobot;
use App\UiPathProcess;
use App\UiPathQueue;
use App\Alert;
use App\AlertTrigger;
use App\AlertTriggerDefinition;
use App\AlertTriggerRule;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

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
        $alertTriggers = AlertTrigger::all()->where('deleted', false);
        
        return view('configuration.alert-trigger.index', [
            'page' => 'configuration.alert-trigger.index',
            'alerts' => $alerts,
            'clients' => $clients->sortBy('name'),
            'clientsCount' => $clients->count(),
            'watchedAutomatedProcessesCount' => WatchedAutomatedProcess::all()->count(),
            'robotsCount' => UiPathRobot::all()->count(),
            'alertTriggersCount' => $alertTriggers->count(),
            'alertTriggers' => $alertTriggers,
            'openedAlertsCount' => Alert::where('closed', false)->count(),
            'underRevisionAlertsCount' => Alert::where('under_revision', true)->count(),
            'closedAlertsCount' => Alert::where('closed', true)->count(),
            'orchestratorsCount' => UiPathOrchestrator::all()->count()
        ]);
    }

    /**
     * Show the edit form.
     *
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request, AlertTrigger $alertTrigger)
    {
        return view('configuration.alert-trigger.form.edit', [
            'alertTrigger' => $alertTrigger,
            'clients' => Client::all()->sortBy('name')
        ]);
    }

    /**
     * Show the edit buttons.
     *
     * @return \Illuminate\Http\Response
     */
    public function editButtons(Request $request, AlertTrigger $alertTrigger)
    {
        return view('configuration.alert-trigger.form.edit-buttons', [
            'alertTrigger' => $alertTrigger
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
            'type' => $type,
            'is_triggered_on_monday' => $watchedAutomatedProcess->running_period_monday,
            'is_triggered_on_tuesday' => $watchedAutomatedProcess->running_period_tuesday,
            'is_triggered_on_wednesday' => $watchedAutomatedProcess->running_period_wednesday,
            'is_triggered_on_thursday' => $watchedAutomatedProcess->running_period_thursday,
            'is_triggered_on_friday' => $watchedAutomatedProcess->running_period_friday,
            'is_triggered_on_saturday' => $watchedAutomatedProcess->running_period_saturday,
            'is_triggered_on_sunday' => $watchedAutomatedProcess->running_period_sunday,
        ]);
        return view("configuration.alert-trigger.steps.content.trigger-details.alert-definition.rule.element", [
            'alertTriggerRule' => $alertTriggerRule,
            'watchedAutomatedProcess' => $watchedAutomatedProcess
        ]);
    }

    /**
     * Show default alert trigger summary (for creation).
     *
     * @return \Illuminate\Http\Response
     */
    public function defaultAlertTriggerSummary(Request $request, WatchedAutomatedProcess $watchedAutomatedProcess)
    {
        $title = $request->get('title');
        $definitions = $request->get('definitions');
        
        $alertTrigger = new AlertTrigger([
            'title' => $title,
            'watched_automated_process_id' => $watchedAutomatedProcess->id
        ]);

        foreach ($definitions as $definition) {
            $alertTriggerDefinition = new AlertTriggerDefinition([
                'rank' => $definition['rank'],
                'level' => $definition['level']
            ]);

            foreach ($definition['rules'] as $rule) {
                $parameters = $rule['parameters'];

                $alertTriggerRule = new AlertTriggerRule([
                    'rank' => $rule['rank'],
                    'type' => $rule['type'],
                    'parameters' => $parameters['specific'],
                    'time_slot_from' => $parameters['standard']['timeSlot']['from'],
                    'time_slot_until' => $parameters['standard']['timeSlot']['to'],
                    'has_relative_time_slot' => 
                        array_key_exists('relativeTimeSlot', $parameters['standard'])
                        && $parameters['standard']['relativeTimeSlot'] !== null,
                    'relative_time_slot_duration' =>
                        array_key_exists('relativeTimeSlot', $parameters['standard'])
                        ? $parameters['standard']['relativeTimeSlot']
                        : null,
                    'is_triggered_on_monday' => $parameters['standard']['triggeringDays']['monday'],
                    'is_triggered_on_tuesday' => $parameters['standard']['triggeringDays']['tuesday'],
                    'is_triggered_on_wednesday' => $parameters['standard']['triggeringDays']['wednesday'],
                    'is_triggered_on_thursday' => $parameters['standard']['triggeringDays']['thursday'],
                    'is_triggered_on_friday' => $parameters['standard']['triggeringDays']['friday'],
                    'is_triggered_on_saturday' => $parameters['standard']['triggeringDays']['saturday'],
                    'is_triggered_on_sunday' => $parameters['standard']['triggeringDays']['sunday']
                ]);

                $involvedEntities = $parameters['standard']['involvedEntities'];
                $involvedProcesses = array_key_exists('processes', $involvedEntities) ? $involvedEntities['processes'] : [];
                $involvedRobots = array_key_exists('robots', $involvedEntities) ? $involvedEntities['robots'] : [];
                $involvedQueues = array_key_exists('queues', $involvedEntities) ? $involvedEntities['queues'] : [];

                $uiPathProcesses = UiPathProcess::find($involvedProcesses);
                foreach ($uiPathProcesses as $uiPathProcess) {
                    $alertTriggerRule->processes->add($uiPathProcess);
                }

                $uiPathRobots = UiPathRobot::find($involvedRobots);
                foreach ($uiPathRobots as $uiPathRobot) {
                    $alertTriggerRule->robots->add($uiPathRobot);
                }

                $uiPathQueues = UiPathQueue::find($involvedQueues);
                foreach ($uiPathQueues as $uiPathQueue) {
                    $alertTriggerRule->queues->add($uiPathQueue);
                }

                $alertTriggerDefinition->rules->add($alertTriggerRule);
            }

            $alertTrigger->definitions->add($alertTriggerDefinition);
        }
        
        return [
            'view' => view('configuration.alert-trigger.steps.content.summary.index', [
                'alertTrigger' => $alertTrigger
            ])->render(),
            'alertTrigger' => $alertTrigger
        ];
    }

    /**
     * Show alert trigger confirmation (after creation).
     *
     * @return \Illuminate\Http\Response
     */
    public function alertTriggerCreationConfirmation(AlertTrigger $alertTrigger)
    {
        return view('configuration.alert-trigger.steps.content.confirmation', [
            'alertTrigger' => $alertTrigger
        ]);
    }

    /**
     * Show the alert triggers as table.
     *
     * @return \Illuminate\Http\Response
     */
    public function table(Request $request)
    {
        $alertTriggers = AlertTrigger::all()->where('deleted', false);
        return view('configuration.alert-trigger.table')
            ->with('alertTriggers', $alertTriggers);
    }
}