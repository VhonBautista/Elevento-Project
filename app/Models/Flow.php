<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Flow extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'segment_id',
        'timeline',
        'start_time',
        'end_time',
        'list',
    ];

    public function segment()
    {
        return $this->belongsTo(Segment::class);
    }
}
