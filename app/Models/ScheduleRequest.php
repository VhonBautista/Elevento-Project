<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ScheduleRequest extends Model
{
    use HasFactory;

    protected $fillable = [
        'event_id',
        'new_start',
        'new_end',
        'reason',
    ];

    public function event()
    {
        return $this->belongsTo(Event::class);
    }
}
