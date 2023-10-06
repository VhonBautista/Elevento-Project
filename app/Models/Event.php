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
        'desciption',
        'cover_photo',
        'start_date',
        'end_date',
        'creator_id',
        'campus',
        'venue_id',
        'event_type',
    ];
}
