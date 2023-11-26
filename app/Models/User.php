<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'role',
        'is_disabled',
        'manage_user',
        'manage_venue',
        'manage_campus',
        'manage_event',
        'username',
        'profile_picture',
        'email',
        'password',
        'user_id',
        'organization_id',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    public function campusEntity()
    {
        return $this->hasOne(CampusEntity::class, 'user_id', 'user_id');
    }

    public function organization()
    {
        return $this->belongsTo(Organization::class);
    }

    public function projects()
    {
        return $this->hasMany(Project::class);
    }

    public function planningLog()
    {
        return $this->hasOne(PlanningLog::class);
    }

    public function createdEvents()
    {
        return $this->hasMany(Event::class, 'creator_id');
    }

    public function registrations()
    {
        return $this->hasMany(Registration::class);
    }

    public function favorites()
    {
        return $this->hasMany(Favorite::class);
    }
}
