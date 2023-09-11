<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Department extends Model
{
    use HasFactory;

    protected $table = 'departments';

    protected $fillable = [
        'department_code',
        'department',
        'campus',
    ];
    
    public function campusEntities()
    {
        return $this->hasMany(CampusEntity::class, 'department_code', 'department_code');
    }
}
