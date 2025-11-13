<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\EventRegistration;
use App\Models\Comment;
use App\Models\VolunteerRole;

class Event extends Model
{
    use HasFactory;

    protected $fillable = [
        'organizer_id',
        'title',
        'description',
        'location',
        'start_at',
        'volunteers_needed',
    ];

    protected $casts = [
        'start_at' => 'datetime',
    ];

    public function organizer()
    {
        return $this->belongsTo(User::class, 'organizer_id');
    }

    public function registrations()
    {
        return $this->hasMany(EventRegistration::class);
    }

    public function attendees(){
        // Liste des utilisateurs inscrits
        return $this->belongsToMany(User::class, 'event_registrations')
            ->withTimestamps();
    }
    
    public function comments()
    {
        return $this->morphMany(Comment::class, 'commentable');
    }

    public function volunteerRoles()
    {
        return $this->hasMany(VolunteerRole::class);
    }

    public function volunteers()
    {
        return $this->belongsToMany(User::class, 'event_volunteers')
            ->withTimestamps()
            ->withPivot('volunteer_role_id');
    }
}