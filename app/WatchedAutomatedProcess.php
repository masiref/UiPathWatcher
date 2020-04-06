<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Carbon\Carbon;

class WatchedAutomatedProcess extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'client_id', 'name', 'code', 'operational_handbook_page_url',
        'kibana_dashboard_url', 'additional_information', 'running_period_monday',
        'running_period_tuesday', 'running_period_wednesday', 'running_period_thursday',
        'running_period_friday', 'running_period_saturday', 'running_period_sunday',
        'running_period_time_from', 'running_period_time_until'
    ];

    /**
     * The relationships that should always be loaded.
     *
     * @var array
     */
    protected $with = ['alerts'];

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
     * Get the client that owns the watched automated process.
     */
    public function client()
    {
        return $this->belongsTo('App\Client');
    }

    /**
     * The processes that belong to the watched automated process.
     */
    public function processes()
    {
        return $this->belongsToMany('App\UiPathProcess');
    }

    /**
     * The robots that belong to the watched automated process.
     */
    public function robots()
    {
        return $this->belongsToMany('App\UiPathRobot');
    }

    /**
     * The queues that belong to the watched automated process.
     */
    public function queues()
    {
        return $this->belongsToMany('App\UiPathQueue');
    }

    /**
     * The alerts that belong to the watched automated process.
     */
    public function alerts()
    {
        return $this->hasMany('App\Alert');
    }

    /**
     * The opened alerts that belong to the watched automated process.
     */
    public function openedAlerts()
    {
        return $this->alerts()->get()->where('closed', false)->sortBy(function($alert) {
            return $alert->levelOrder();
        })->sortBy('created_at');
    }

    /**
     * The closed alerts that belong to the watched automated process.
     */
    public function closedAlerts()
    {
        return $this->alerts()->get()->where('closed', true)->sortByDesc('created_at');
    }

    /**
     * The under revision alerts that belong to the watched automated process.
     */
    public function underRevisionAlerts()
    {
        return $this->alerts()->get()->where('under_revision', true)->sortByDesc('created_at');
    }

    /**
     * The higher alert level in its opened alerts (danger, warning, info)
     */
    public function higherAlertLevel()
    {
        $openedAlerts = $this->openedAlerts();
        
        if (count($openedAlerts->where('level', 'danger')) > 0 || $this->onlineRobotsCount() < $this->robots->count())
            return 'danger';
        
        if (count($openedAlerts->where('level', 'warning')) > 0 || $this->loggingRobotsCount() < $this->robots->count())
            return 'warning';
        
        if (count($openedAlerts->where('level', 'info')) > 0)
            return 'info';

        return 'success';
    }

    /**
     * The days on which the watched process is running
     */
    public function runningDays()
    {
        $days = '';
        if (
            $this->running_period_monday &&
            $this->running_period_tuesday &&
            $this->running_period_wednesday &&
            $this->running_period_thursday &&
            $this->running_period_friday &&
            $this->running_period_saturday &&
            $this->running_period_sunday
        ) {
            $days = 'all days';
        } else if (
            $this->running_period_monday &&
            $this->running_period_tuesday &&
            $this->running_period_wednesday &&
            $this->running_period_thursday &&
            $this->running_period_friday
        ) {
            $days = 'week days';
        } else if (
            $this->running_period_saturday &&
            $this->running_period_sunday
        ) {
            $days = 'weekend';
        } else {
            if ($this->running_period_monday) {
                $days.= 'Monday';
            }
            if ($this->running_period_tuesday) {
                $days.= ($days === '' ? '' : ', ') . 'Tuesday';
            }
            if ($this->running_period_wednesday) {
                $days.= ($days === '' ? '' : ', ') . 'Wednesday';
            }
            if ($this->running_period_thursday) {
                $days.= ($days === '' ? '' : ', ') . 'Thursday';
            }
            if ($this->running_period_friday) {
                $days.= ($days === '' ? '' : ', ') . 'Friday';
            }
            if ($this->running_period_saturday) {
                $days.= ($days === '' ? '' : ', ') . 'Saturday';
            }
            if ($this->running_period_sunday) {
                $days.= ($days === '' ? '' : ', ') . 'Sunday';
            }
            $search = ',';
            $replace = ' and';
            $days = strrev(implode(strrev($replace), explode(strrev($search), strrev($days), 2)));
        }
        return $days;
    }

    /**
     * The days and hours on which the process is running
     */
    public function runningPeriod()
    {
        $days = $this->runningDays();
        $timeFrom = Carbon::createFromTimeString($this->running_period_time_from)->format('g:i A');
        $timeUntil = Carbon::createFromTimeString($this->running_period_time_until)->format('g:i A');
        return "Running from $timeFrom until $timeUntil on $days";
    }

    public function onlineRobotsCount()
    {
        return $this->robots()->where('is_online', true)->count();
    }

    public function loggingRobotsCount()
    {
        return $this->robots()->where('is_logging', true)->count();
    }
}
