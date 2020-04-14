<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\AlertTriggerShutdown;
use App\Library\Services\AlertTriggerService;
use App\Library\Services\UiPathOrchestratorService;
use Illuminate\Support\Facades\Auth;
use GuzzleHttp\Client as Guzzle;
use GuzzleHttp\Exception\RequestException;
use Carbon\Carbon;
use App\AlertTrigger;
use App\Alert;
use App\AlertTriggerRule;

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

    public function debug(AlertTriggerService $service)
    {
        $rule = AlertTriggerRule::find(4);
        return $service->verifyRule($rule, Carbon::now()) ? 'verified' : 'not verified';
    }
}
