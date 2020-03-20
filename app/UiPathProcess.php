<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UiPathProcess extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'ui_path_orchestrator_id', 'name', 'description', 'version', 'external_id',
        'environment_name', 'external_environment_id'
    ];

    public function __toString()
    {
        return $this->name . " - " . $this->version;
    }

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
