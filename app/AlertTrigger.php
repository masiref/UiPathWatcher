<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AlertTrigger extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'title', 'watched_automated_process_id'
    ];

    /**
     * Get the alerts for the alert trigger.
     */
    public function alerts()
    {
        return $this->hasMany('App\Alert');
    }

    /**
     * Get the watched automated process that owns the alert trigger.
     */
    public function watchedAutomatedProcess()
    {
        return $this->belongsTo('App\WatchedAutomatedProcess');
    }

    /**
     * Get the definitions for the alert trigger.
     */
    public function definitions()
    {
        return $this->hasMany('App\AlertTriggerDefinition');
    }
}