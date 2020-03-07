<?php

namespace App;

use Laravel\Passport\HasApiTokens;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasApiTokens, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * Get the alerts under revision or closed by the user.
     */
    public function alerts()
    {
        return $this->hasMany('App\Alert', 'reviewer_id');
    }

    /**
     * The opened alerts that belong to the user.
     */
    public function openedAlerts()
    {
        return $this->alerts()->get()->where('closed', false)->sortBy(function($alert) {
            return $alert->levelOrder();
        })->sortBy('created_at');
    }

    /**
     * The closed alerts that belong to the user.
     */
    public function closedAlerts()
    {
        return $this->alerts()->get()->where('closed', true)->sortByDesc('created_at');
    }

    /**
     * The under revision alerts that belong to the user.
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
}
