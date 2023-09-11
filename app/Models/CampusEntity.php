<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CampusEntity extends Model
{
    use HasFactory;

    protected $table = 'campus_entities';

    protected $fillable = [
        'user_id',
        'firstname',
        'lastname',
        'middlename',
        'type',
        'sex',
        'campus',
        'department_code',
    ];
    
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'user_id');
    }
    
    public function department()
    {
        return $this->belongsTo(Department::class, 'department_code', 'department_code');
    }
}
