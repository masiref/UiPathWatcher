<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AlertTriggerRule extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'alert_trigger_definition_id', 'type', 'rank'
    ];

    /**
     * Get the alert trigger definition that owns the alert trigger rule.
     */
    public function definition()
    {
        return $this->belongsTo('App\AlertTriggerDefinition', 'alert_trigger_definition_id');
    }
}