<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class RunningSession extends Model
{
    use HasFactory;

    protected $fillable = [
        'organizer_id','title','description','location','city','zipcode','start_at',
        'distance_km_min','distance_km_max','pace_min_per_km_min','pace_min_per_km_max',
        'duration_min','duration_max','max_participants', 
        'latitude','longitude',
    ];

    protected $casts = [
        'start_at' => 'datetime',
        'distance_km_min' => 'decimal:2',
        'distance_km_max' => 'decimal:2',
        'pace_min_per_km_min' => 'decimal:2',
        'pace_min_per_km_max' => 'decimal:2',
    ];

    public function organizer(){ return $this->belongsTo(User::class, 'organizer_id'); }

    public function participations(){ return $this->hasMany(RunningSessionParticipation::class); }

    public function attendees(){
        return $this->belongsToMany(User::class, 'running_session_participations')
            ->withTimestamps();
    }
    
    public function comments()
    {
        return $this->morphMany(\App\Models\Comment::class, 'commentable');
    }
}
