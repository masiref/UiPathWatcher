<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UiPathRobot extends Model
{
    /**
     * Get the orchestrator associated with the robot.
     */
    public function orchestrator()
    {
        return $this->hasOne('App\UiPathOrchestrator');
    }

    /**
     * The environments that belong to the robot.
     */
    public function environments()
    {
        return $this->belongsToMany('App\UiPathEnvironment');
    }
}
