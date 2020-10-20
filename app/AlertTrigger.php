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
        'title', 'watched_automated_process_id', 'active', 'ignored',
        'ignored_from', 'ignored_until', 'ignorance_description', 'deleted',
        'deleted_at', 'definitions'
    ];
    
    public function setDeleted($deleted = true)
    {
        $this->deleted = $deleted;
        if ($deleted) {
            $this->active = false;
        }
        foreach ($this->definitions as $definition) {
            $definition->deleted = $deleted;
            foreach ($definition->rules as $rule) {
                $rule->deleted = $deleted;
                $rule->save();
            }
            $definition->save();
        }
        $this->save();
    }

    /**
     * Get the alerts for the alert trigger.
     */
    public function alerts()
    {
        return $this->hasMany('App\Alert');
    }

    /**
     * The opened alerts that belong to the trigger
     */
    public function openedAlerts()
    {
        return $this->alerts()->get()->where('closed', false)->sortBy(function($alert) {
            return $alert->levelOrder();
        })->sortBy('created_at');
    }

    /**
     * The closed alerts that belong to the trigger
     */
    public function closedAlerts()
    {
        return $this->alerts()->get()->where('closed', true)->where('parent', null)->sortBy('closed_at');
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