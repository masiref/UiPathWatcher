<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UiPathQueue extends Model
{
    /**
     * The relationships that should always be loaded.
     *
     * @var array
     */
    protected $with = ['orchestrator'];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'ui_path_orchestrator_id', 'name', 'description', 'external_id'
    ];

    public function __toString()
    {
        return $this->name;
    }

    /**
     * Get the orchestrator associated with the queue.
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
}
