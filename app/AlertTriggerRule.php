<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class AlertTriggerRule extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'alert_trigger_definition_id', 'type', 'rank', 'time_slot_from',
        'time_slot_until', 'has_relative_time_slot', 'relative_time_slot_duration',
        'is_triggered_on_monday', 'is_triggered_on_tuesday', 'is_triggered_on_wednesday',
        'is_triggered_on_thursday', 'is_triggered_on_friday', 'is_triggered_on_saturday',
        'is_triggered_on_sunday', 'parameters'
    ];

    protected $casts = [
        'parameters' => 'array'
    ];

    /**
     * Get the alert trigger definition that owns the alert trigger rule.
     */
    public function definition()
    {
        return $this->belongsTo('App\AlertTriggerDefinition', 'alert_trigger_definition_id');
    }

    /**
     * The processes that belong to the alert trigger rule.
     */
    public function processes()
    {
        return $this->belongsToMany('App\UiPathProcess');
    }

    /**
     * The robots that belong to the alert trigger rule.
     */
    public function robots()
    {
        return $this->belongsToMany('App\UiPathRobot');
    }

    /**
     * The queues that belong to the alert trigger rule.
     */
    public function queues()
    {
        return $this->belongsToMany('App\UiPathQueue');
    }

    /**
     * The time (in readable format) from which the rule should be triggered
     */
    public function timeSlotFromReadable()
    {
        return Carbon::createFromTimeString($this->time_slot_from)->format('g:i A');
    }

    /**
     * The time (in readable format) until which the rule should be triggered
     */
    public function timeSlotUntilReadable()
    {
        return Carbon::createFromTimeString($this->time_slot_until)->format('g:i A');
    }

    /**
     * The days on which the rule should be triggered
     */
    public function triggeringDays()
    {
        $days = '';
        if (
            $this->is_triggered_on_monday &&
            $this->is_triggered_on_tuesday &&
            $this->is_triggered_on_wednesday &&
            $this->is_triggered_on_thursday &&
            $this->is_triggered_on_friday &&
            $this->is_triggered_on_saturday &&
            $this->is_triggered_on_sunday
        ) {
            $days = 'all days';
        } else if (
            $this->is_triggered_on_monday &&
            $this->is_triggered_on_tuesday &&
            $this->is_triggered_on_wednesday &&
            $this->is_triggered_on_thursday &&
            $this->is_triggered_on_friday
        ) {
            $days = 'week days';
        } else if (
            $this->is_triggered_on_saturday &&
            $this->is_triggered_on_sunday
        ) {
            $days = 'weekend';
        } else {
            if ($this->is_triggered_on_monday) {
                $days.= 'Monday';
            }
            if ($this->is_triggered_on_tuesday) {
                $days.= ($days === '' ? '' : ', ') . 'Tuesday';
            }
            if ($this->is_triggered_on_wednesday) {
                $days.= ($days === '' ? '' : ', ') . 'Wednesday';
            }
            if ($this->is_triggered_on_thursday) {
                $days.= ($days === '' ? '' : ', ') . 'Thursday';
            }
            if ($this->is_triggered_on_friday) {
                $days.= ($days === '' ? '' : ', ') . 'Friday';
            }
            if ($this->is_triggered_on_saturday) {
                $days.= ($days === '' ? '' : ', ') . 'Saturday';
            }
            if ($this->is_triggered_on_sunday) {
                $days.= ($days === '' ? '' : ', ') . 'Sunday';
            }
            $search = ',';
            $replace = ' and';
            $days = strrev(implode(strrev($replace), explode(strrev($search), strrev($days), 2)));
        }
        return $days;
    }
}