<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UpgradeRequest extends Model
{
    use HasFactory;

    protected $table = 'requests';

    protected $fillable = [
        'status',
        'message',
        'user_id',
        'organization_id',
    ];
}
