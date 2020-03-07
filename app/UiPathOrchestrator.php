<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UiPathOrchestrator extends Model
{
    /**
     * Get the clients for the orchestrator.
     */
    public function clients()
    {
        return $this->hasMany('App\Client');
    }

    /**
     * Get the processes for the orchestrator.
     */
    public function processes()
    {
        return $this->hasMany('App\UiPathProcess');
    }

    /**
     * Get the environments for the orchestrator.
     */
    public function environments()
    {
        return $this->hasMany('App\UiPathEnvironment');
    }

    /**
     * Get the robots for the orchestrator.
     */
    public function robots()
    {
        return $this->hasMany('App\UiPathRobot');
    }
}
