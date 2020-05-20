<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AlertTriggerDefinition extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'alert_trigger_id', 'level', 'rank', 'deleted',
        'deleted_at'
    ];
    /**
     * The relationships that should always be loaded.
     *
     * @var array
     */
    //protected $with = ['rules'];

    /**
     * Get the alert trigger that owns the alert trigger definition.
     */
    public function trigger()
    {
        return $this->belongsTo('App\AlertTrigger', 'alert_trigger_id');
    }

    /**
     * Get the rules for the alert trigger definition.
     */
    public function rules()
    {
        return $this->hasMany('App\AlertTriggerRule');
    }

    /**
     * Get alert trigger definition's level order
     **/
    public function levelOrder()
    {
       switch ($this->level)
       {
           case 'danger':
               return 1;
           case 'warning':
               return 2;
           case 'info':
               return 3;
       }
    }
}