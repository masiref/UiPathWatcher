<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use App\User;
use Carbon\Carbon;

class Alert extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'parent_id', 'alert_trigger_id', 'alert_trigger_definition_id', 'watched_automated_process_id',
        'reviewer_id', 'under_revision', 'revision_started_at', 'closed', 'closed_at', 'closing_description',
        'messages', 'auto_closed'
    ];

    protected $casts = [
        'messages' => 'array'
    ];

    /**
     * The relationships that should always be loaded.
     *
     * @var array
     */
    protected $with = ['trigger', 'definition', 'reviewer'];

    protected static function boot()
    {
        parent::boot();

        static::addGlobalScope('order', function (Builder $builder) {
            $builder->orderBy('created_at', 'desc');
        });
    }

    /**
     * Get the parent that owns the alert.
     */
    public function parent()
    {
        return $this->belongsTo('App\Alert', 'parent_id');
    }

    /**
     * Get the alert trigger that owns the alert.
     */
    public function trigger()
    {
        return $this->belongsTo('App\AlertTrigger', 'alert_trigger_id');
    }

    /**
     * Get the alert trigger definition that owns the alert.
     */
    public function definition()
    {
        return $this->belongsTo('App\AlertTriggerDefinition', 'alert_trigger_definition_id');
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
     * The categories that belong to the alert.
     */
    public function categories()
    {
        return $this->belongsToMany('App\AlertCategory');
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
     public function close($falsePositive, $description, $categories)
     {
        $this->closed_at = Carbon::now();
        $this->under_revision = false;
        $this->false_positive = $falsePositive;
        $this->closed = true;
        $this->closing_description = $description;

        $alertCategories = AlertCategory::find($categories);
        $this->categories()->attach($alertCategories);
        
        return $this->save();
     }

     /**
      * Ignore alert
      **/
    public function ignore($from, $fromTime, $to, $toTime, $description, $categories)
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
        $this->closing_description = $description;
        $this->trigger->ignorance_description = $description;
        $this->trigger->save();

        $alertCategories = AlertCategory::find($categories);
        $this->categories()->attach($alertCategories);
        
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

    public function createdAtDayDateTime()
    {
        $date = Carbon::createFromFormat('Y-m-d H:i:s', $this->created_at);
        return $date->toDayDateTimeString();
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
       switch ($this->definition->level)
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
