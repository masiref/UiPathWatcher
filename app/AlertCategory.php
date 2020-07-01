<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class AlertCategory extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'label'
    ];

    /**
     * The alerts that belong to the categories.
     */
    public function alerts()
    {
        return $this->belongsToMany('App\Alert');
    }

}