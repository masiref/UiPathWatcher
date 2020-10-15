<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\AlertTriggerShutdown;
use App\Library\Services\AlertTriggerService;
use App\Library\Services\UiPathOrchestratorService;
use App\Library\Services\ElasticSearchService;
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
use App\Client;
use App\UiPathRobot;

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

    public function debug(ElasticSearchService $elasticSearchService)
    {
        $until = Carbon::now();
        $from = $until->copy()->subMinutes(15);
        $result = $elasticSearchService->search(Client::find(3), "machineName: 'DK968005' OR robotName: 'DK968005'", $from, $until);

        return $result;
    }

    public function debugRule($id = 1, AlertTriggerService $service)
    {
        return $service->verifyRule(
            AlertTriggerRule::find($id),
            Carbon::now()
        );

        return $alert;
    }
}
