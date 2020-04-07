<?php

namespace App\Library\Services;

use App\AlertTriggerShutdown;
use App\AlertTriggerRule;

class AlertTriggerService {

    public function isUnderShutdown()
    {
        return AlertTriggerShutdown::where('ended_at', null)->count() > 0;
    }

    public function currentShutdown()
    {
        return AlertTriggerShutdown::where('ended_at', null)->first();
    }

    public function verifyRule(AlertTriggerRule $rule)
    {
        return true;
    }
}