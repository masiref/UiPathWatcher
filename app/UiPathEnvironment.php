<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UiPathEnvironment extends Model
{
    /**
     * Get the orchestrator associated with the environment.
     */
    public function orchestrator()
    {
        return $this->hasOne('App\UiPathOrchestrator');
    }

    /**
     * The robots that belong to the environment.
     */
    public function robots()
    {
        return $this->belongsToMany('App\UiPathRobot');
    }
}
