<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    use HasFactory;

    protected $table = 'events';

    protected $fillable = [
        'status',
        'register',
        'hearts',
        'title',
        'description',
        'cover_photo',
        'start_date',
        'end_date',
        'creator_id',
        'campus',
        'venue_id',
        'event_type',
        'target_audience',
    ];

    public function venue()
    {
        return $this->belongsTo(Venue::class);
    }

    public function projects()
    {
        return $this->belongsToMany(Project::class, 'projects');
    }

    public function rescheduleRequest()
    {
        return $this->hasOne(ScheduleRequest::class);
    }
    
    public function creator()
    {
        return $this->belongsTo(User::class, 'creator_id');
    }

    public function segments()
    {
        return $this->hasMany(Segment::class);
    }

    public function peoples()
    {
        return $this->hasMany(EventPeople::class);
    }

    public function planningLogs()
    {
        return $this->hasMany(PlanningLog::class);
    }
}
