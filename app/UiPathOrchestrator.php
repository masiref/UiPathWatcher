<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UiPathOrchestrator extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'code', 'url'
    ];

    public function __toString()
    {
        return $this->name . " | " . $this->url;
    }

    /**
     * Get the clients for the orchestrator.
     */
    public function clients()
    {
        return $this->hasMany('App\Client');
    }

    /**
     * Get the processes for the orchestrator.
     */
    public function processes()
    {
        return $this->hasMany('App\UiPathProcess');
    }

    /**
     * Get the robots for the orchestrator.
     */
    public function robots()
    {
        return $this->hasMany('App\UiPathRobot');
    }
}
