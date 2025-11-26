<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Event;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class VolunteerRole extends Model
{
    use HasFactory;

    protected $fillable = ['event_id','name','max_slots'];

    public function event()
    {
        return $this->belongsTo(Event::class);
    }

    public function volunteers()
    {
        return $this->belongsToMany(User::class, 'event_volunteers')
            ->withTimestamps();
    }
}
