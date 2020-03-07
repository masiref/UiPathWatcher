<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UiPathProcess extends Model
{
    /**
     * Get the orchestrator associated with the process.
     */
    public function orchestrator()
    {
        return $this->hasOne('App\UiPathOrchestrator');
    }

    /**
     * Get the environment associated with the process.
     */
    public function environment()
    {
        return $this->hasOne('App\UiPathEnvironment');
    }

    /**
     * The watched automated processes that belong to the process.
     */
    public function watchedAutomatedProcesses()
    {
        return $this->belongsToMany('App\WatchedAutomatedProcess');
    }
}
