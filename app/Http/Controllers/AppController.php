<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\AlertTriggerShutdown;
use App\Library\Services\AlertTriggerService;
use App\Library\Services\UiPathOrchestratorService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Notification;
use GuzzleHttp\Client as Guzzle;
use GuzzleHttp\Exception\RequestException;
use Carbon\Carbon;
use App\AlertTrigger;
use App\Alert;
use App\AlertTriggerRule;
use App\AlertTriggerDefinition;
use App\Notifications\AlertTriggered;
use App\User;

class AppController extends Controller
{

    public function shutdownAlertTriggers(Request $request, AlertTriggerService $service)
    {
        $reason = $request->get('reason');
        if (!$service->isUnderShutdown()) {
            return AlertTriggerShutdown::create([
                'reason' => $reason
            ]);
        }
        return null;
    }

    public function reactivateAlertTriggers(Request $request, AlertTriggerService $service)
    {
        $reason = $request->get('reason');
        if ($service->isUnderShutdown()) {
            $shutdown = $service->currentShutdown();
            if ($shutdown->update([
                'ended_at' => Carbon::now(),
                'ended_reason' => $reason
            ])) {
                return $shutdown;
            };
        }
        return null;
    }

    public function notifications(Request $request)
    {
        $user = Auth::user();
        if ($user) {
            $notifications = $user->unreadNotifications;
            $notifications->markAsRead();
            return $notifications;
        }
        return array();
    }

    public function debug(AlertTriggerService $service)
    {
        return $service->verifyRule(
            AlertTriggerRule::find(7),
            Carbon::now()
        );
        
        /*$trigger = AlertTrigger::find(1);
        $definition = AlertTriggerDefinition::find(1);
        $wap = $trigger->watchedAutomatedProcess;
        $alert = Alert::create([
            'alert_trigger_id' => $trigger->id,
            'alert_trigger_definition_id' => $definition->id,
            'watched_automated_process_id' => $wap->id,
            'messages' => array('My first message')
        ]);*/

        /*$ancestor = Alert::find(2);
        $ancestor->update([
            'closed' => true,
            'closed_at' => $alert->created_at,
            'closing_description' => 'Parent alert created',
            'auto_closed' => true,
            'under_revision' => false,
            'parent_id' => $alert->id
        ]);*/

        //$alert = Alert::find(1);
        //Notification::send(User::all(), new AlertTriggered($alert));

        return $alert;
    }
}
