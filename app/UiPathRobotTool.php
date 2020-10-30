<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UiPathRobotTool extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'label', 'process_name', 'color'
    ];

    public function __toString()
    {
        return $this->label;
    }
}
