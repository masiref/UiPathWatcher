<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\User;
use Carbon\Carbon;

class Alert extends Model
{
    /**
     * The relationships that should always be loaded.
     *
     * @var array
     */
    protected $with = ['reviewer'];

    /**
     * Get the alert trigger that owns the alert.
     */
    public function trigger()
    {
        return $this->belongsTo('App\AlertTrigger', 'alert_trigger_id');
    }

    /**
     * Get the watched automated process that owns the alert.
     */
    public function watchedAutomatedProcess()
    {
        return $this->belongsTo('App\WatchedAutomatedProcess');
    }

    /**
     * Get the user that is reviewing the alert.
     */
    public function reviewer()
    {
        return $this->belongsTo('App\User');
    }

    /**
     * Turn alert under revision
     */
    public function enterRevisionMode(User $reviewer)
    {
        $this->revision_started_at = Carbon::now();
        $this->under_revision = true;
        $this->reviewer()->associate($reviewer);
        
        return $this->save();
    }

    /**
     * Exit alert from review mode.
     */
    public function exitRevisionMode()
    {
        $this->under_revision = false;
        $this->revision_started_at = null;
        $this->reviewer()->dissociate();
        
        return $this->save();
    }

    /**
     * Close alert
     */
     public function close($falsePositive, $description)
     {
        $this->closed_at = Carbon::now();
        $this->under_revision = false;
        $this->false_positive = $falsePositive;
        $this->closed = true;
        $this->closing_description = $description;
        
        return $this->save();
     }

     /**
      * Ignore alert
      **/
    public function ignore($from, $fromTime, $to, $toTime, $description)
    {
        $this->closed_at = Carbon::now();
        $this->under_revision = false;
        $this->trigger->ignored_from = Carbon::createFromFormat('Y-m-d H:i:s', "$from $fromTime");
        if ($to !== null && $toTime !== null) {
            $this->trigger->ignored_until = Carbon::createFromFormat('Y-m-d H:i:s', "$to $toTime");
        }
        $this->trigger->ignored = true;
        $this->ignored = true;
        $this->closed = true;
        $this->ignorance_description = $description;
        
        return $this->save();
    }

    /**
     * Get alert's creation date as a string
     */
    public function createdAt()
    {
        $date = Carbon::createFromFormat('Y-m-d H:i:s', $this->created_at);
        return $date->format('d/m/Y H:i:s');
    }

    /**
     * Get alert's creation date difference from now for humans (eg: 1 hour ago)
     */
    public function createdAtDiffForHumans()
    {
        $date = Carbon::createFromFormat('Y-m-d H:i:s', $this->created_at);
        return $date->diffForHumans(Carbon::now());
    }

    /**
     * Get alert's revision start date as a string
     */
    public function revisionStartedAt()
    {
        if ($this->under_revision) {
            $date = Carbon::createFromFormat('Y-m-d H:i:s', $this->revision_started_at);
            return $date->format('d/m/Y H:i:s');
        }
        return '';
    }

    /**
     * Get alert's revision start date difference from now for humans (eg: 1 hour ago)
     */
    public function revisionStartedAtDiffForHumans()
    {
        if ($this->under_revision) {
            $date = Carbon::createFromFormat('Y-m-d H:i:s', $this->revision_started_at);
            return $date->diffForHumans(Carbon::now());
        }  
        return '';
    }

    /**
     * Get alert's revision start date as a timestamp
     */
    public function revisionStartedAtTimestamp()
    {
        if ($this->under_revision) {
            $date = Carbon::createFromFormat('Y-m-d H:i:s', $this->revision_started_at);
            return $date->getTimestamp();
        }
        return 0;
    }

    /**
     * Get alert's closing date as a string
     */
     public function closedAt()
    {
        if ($this->closed) {
            $date = Carbon::createFromFormat('Y-m-d H:i:s', $this->closed_at);
            return $date->format('d/m/Y H:i:s');
        }
        return '';
    }

    /**
     * Get alert's closing date difference from now for humans (eg: 1 hour ago)
     */
    public function closedAtDiffForHumans()
    {
        if ($this->closed) {
            $date = Carbon::createFromFormat('Y-m-d H:i:s', $this->closed_at);
            return $date->diffForHumans(Carbon::now());
        }
        return '';
    }

    /**
     * Get alert's revision start date as a timestamp
     */
    public function closedAtTimestamp()
    {
        if ($this->closed) {
            $date = Carbon::createFromFormat('Y-m-d H:i:s', $this->closed_at);
            return $date->getTimestamp();
        }
        return 0;
    }

    /**
     * Get alert's related client
     */
    public function client()
    {
        return $this->watchedAutomatedProcess->client;
    }

    /**
     * Get alert's level order
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
