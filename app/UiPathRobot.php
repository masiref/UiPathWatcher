<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UiPathRobot extends Model
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
        'ui_path_orchestrator_id', 'name', 'machine_name', 'description', 'username',
        'type', 'is_online', 'is_logging', 'external_id'
    ];

    public function __toString()
    {
        return $this->machine_name;
    }

    /**
     * Get the orchestrator associated with the robot.
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
     * The alert trigger rules that belong to the robot.
     */
    public function alertTriggerRules()
    {
        return $this->belongsToMany('App\AlertTriggerRule');
    }

    /**
     * Get robot's level
     **/
    public function level()
    {
        $level = 'success';
        if (!$this->is_logging) {
            $level = 'warning';
        }
        if (!$this->is_online) {
            $level = 'danger';
        }
        return $level;
    }
}
