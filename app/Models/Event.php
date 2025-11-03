<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    use HasFactory;

    protected $fillable = [
        'organizer_id',
        'title',
        'description',
        'location',
        'start_at',
    ];

    protected $casts = [
        'start_at' => 'datetime',
    ];

    public function organizer()
    {
        return $this->belongsTo(User::class, 'organizer_id');
    }

    public function registrations(){ return $this->hasMany(\App\Models\EventRegistration::class); }

    public function attendees(){
        // Liste des utilisateurs inscrits
        return $this->belongsToMany(\App\Models\User::class, 'event_registrations')
            ->withTimestamps();
    }
    
    public function comments()
    {
        return $this->morphMany(\App\Models\Comment::class, 'commentable');
    }
}