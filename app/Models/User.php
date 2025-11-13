<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use App\Models\RunningSession;
use App\Models\Event;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'city',
        'age',
        'description',
        'profile_photo_path',
        'role',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function attendingEvents(){
        return $this->belongsToMany(Event::class, 'event_registrations')
            ->withTimestamps();
    }

    public function runningSessionsOrganized(){
        return $this->hasMany(RunningSession::class, 'organizer_id');
    }
    public function runningSessionsJoined(){
        return $this->belongsToMany(RunningSession::class, 'running_session_participations')
            ->withTimestamps()
            ->withPivot('status');
    }

    public function volunteeredEvents()
    {
        return $this->belongsToMany(Event::class, 'event_volunteers')
            ->withTimestamps()
            ->withPivot('volunteer_role_id');
    }

    public function isOrganizer(): bool
    {
        return $this->role === 'organizer';
    }

    public function isSporty(): bool
    {
        return $this->role === 'sporty';
    }

}
