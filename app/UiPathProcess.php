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
        return $this->belongsTo('App\UiPathOrchestrator', 'ui_path_orchestrator_id');
    }

    /**
     * The watched automated processes that belong to the process.
     */
    public function watchedAutomatedProcesses()
    {
        return $this->belongsToMany('App\WatchedAutomatedProcess');
    }

    /**
     * The alert trigger rules that belong to the process.
     */
    public function alertTriggerRules()
    {
        return $this->belongsToMany('App\AlertTriggerRule');
    }
}
