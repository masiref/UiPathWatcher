<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Client extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'code'
    ];

    /**
     * The relationships that should always be loaded.
     *
     * @var array
     */
    protected $with = ['watchedAutomatedProcesses', 'orchestrator'];

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
        return $this->belongsTo('App\UiPathOrchestrator');
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
        $dangerAlertsCount = 0;
        $warningAlertsCount = 0;
        $infoAlertsCount = 0;

        foreach ($this->watchedAutomatedProcesses()->get() as $wap)
        {
            $openedAlerts = $wap->openedAlerts();
            $dangerAlertsCount += count($openedAlerts->where('level', 'danger'));
            $warningAlertsCount += count($openedAlerts->where('level', 'warning'));
            $infoAlertsCount += count($openedAlerts->where('level', 'info'));
        }

        if ($dangerAlertsCount > 0)
            return 'danger';
        
        if ($warningAlertsCount > 0)
            return 'warning';
        
        if ($infoAlertsCount > 0)
            return 'info';

        return 'success';
    }
}
