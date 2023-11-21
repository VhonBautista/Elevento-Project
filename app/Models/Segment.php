<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Segment extends Model
{
    use HasFactory;

    protected $fillable = [
        'event_id',
        'date',
    ];

    public function event()
    {
        return $this->belongsTo(Event::class);
    }

    public function flows()
    {
        return $this->hasMany(Flow::class);
    }
}
