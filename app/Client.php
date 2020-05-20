<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class Client extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'code', 'ui_path_orchestrator_id',
        'elastic_search_url', 'elastic_search_index'
    ];

    /**
     * The relationships that should always be loaded.
     *
     * @var array
     */
    protected $with = ['watchedAutomatedProcesses', 'orchestrator'];

    protected static function boot()
    {
        parent::boot();

        static::addGlobalScope('order', function (Builder $builder) {
            $builder->orderBy('name');
        });
    }

    public function __toString()
    {
        return $this->name;
    }

    /**
     * Get the watched automated processes for the client.
     */
    public function watchedAutomatedProcesses()
    {
        return $this->hasMany('App\WatchedAutomatedProcess');
    }

    /**
     * Get the orchestrator that owns the client.
     */
    public function orchestrator()
    {
        return $this->belongsTo('App\UiPathOrchestrator', 'ui_path_orchestrator_id');
    }

    /**
     * The number of watched automated processes for the client.
     */
    public function watchedAutomatedProcessesCount()
    {
        return count($this->watchedAutomatedProcesses()->get());
    }

    /**
     * The number of opened alerts that belong to its watched automated processes.
     */
    public function openedAlertsCount()
    {
        $count = 0;
        foreach ($this->watchedAutomatedProcesses()->get() as $wap)
        {
            $count += count($wap->openedAlerts());
        }

        return $count;
    }

    /**
     * The number of closed alerts that belong to its watched automated processes.
     */
    public function closedAlertsCount()
    {
        $count = 0;
        foreach ($this->watchedAutomatedProcesses()->get() as $wap)
        {
            $count += count($wap->closedAlerts());
        }

        return $count;
    }

    /**
     * The number of under revision alerts that belong to its watched automated processes.
     */
    public function underRevisionAlertsCount()
    {
        $count = 0;
        foreach ($this->watchedAutomatedProcesses()->get() as $wap)
        {
            $count += count($wap->underRevisionAlerts());
        }

        return $count;
    }

    /**
     * The higher alert level in its watched automated processes opened alerts (danger, warning, info)
     */
    public function higherAlertLevel()
    {
        $dangerCount = 0;
        $warningCount = 0;
        $infoCount = 0;

        foreach ($this->watchedAutomatedProcesses()->get() as $wap)
        {
            $dangerCount += $wap->higherAlertLevel() === 'danger' ? 1 : 0;
            $warningCount += $wap->higherAlertLevel() === 'warning' ? 1 : 0;
            $infoCount += $wap->higherAlertLevel() === 'info' ? 1 : 0;
        }

        if ($dangerCount > 0)
            return 'danger';
        
        if ($warningCount > 0)
            return 'warning';
        
        if ($infoCount > 0)
            return 'info';

        return 'success';
    }

    public function robotsCount()
    {
        $count = 0;
        foreach ($this->watchedAutomatedProcesses()->get() as $wap)
        {
            $count += $wap->robots->count();
        }
        return $count;
    }

    public function onlineRobotsCount()
    {
        $count = 0;
        foreach ($this->watchedAutomatedProcesses()->get() as $wap)
        {
            $count += $wap->onlineRobotsCount();
        }
        return $count;
    }

    public function loggingRobotsCount()
    {
        $count = 0;
        foreach ($this->watchedAutomatedProcesses()->get() as $wap)
        {
            $count += $wap->loggingRobotsCount();
        }
        return $count;
    }
}
