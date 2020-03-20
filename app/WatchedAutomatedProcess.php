<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

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
        
        if (count($openedAlerts->where('level', 'danger')) > 0)
            return 'danger';
        
        if (count($openedAlerts->where('level', 'warning')) > 0)
            return 'warning';
        
        if (count($openedAlerts->where('level', 'info')) > 0)
            return 'info';

        return 'success';
    }

    public function runningDays()
    {
        $days = '';
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
        return $days;
    }
}
