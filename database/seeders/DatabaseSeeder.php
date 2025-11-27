<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\RunningSession;
use App\Models\Event;
use App\Models\VolunteerRole;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $organizer = User::create([
            'name' => 'organisateur',
            'email' => 'organisateur@gmail.com',
            'password' => Hash::make('mdp123'),
            'role' => 'organizer',
        ]);
        $sporty = User::create([
            'name' => 'sportif',
            'email' => 'sportif@gmail.com',
            'password' => Hash::make('mdp123'),
            'role' => 'sporty',
        ]);

        RunningSession::create([
            'organizer_id' => $organizer->id,
            'title' => 'Endurance fondamentale à Tournai',
            'description' => 'Une session de course tranquille dans les rues de tournai.',
            'location' => 'Parc de tournai',
            'city' => 'Tournai',
            'zipcode' => '7500',
            'start_at' => now()->addDays(30),
            'distance_km_min' => 5,
            'distance_km_max' => 8,
            'pace_min_per_km_min' => 5,
            'pace_min_per_km_max' => 6,
        ]);
        $event = Event::create([
            'organizer_id' => $organizer->id,
            'title' => 'Marathon de mouscron',
            'description' => 'Le premier Marathon de mouscron.',
            'location' => 'Parc de mouscron',
            'start_at' => now()->addDays(30),
            'volunteers_needed' => 1,
        ]);
        $roles = [
            ['name' => 'Ravitaillement', 'max_slots' => 5],
            ['name' => 'Signaleur', 'max_slots' => 10],
            ['name' => 'Photographe', 'max_slots' => 2],
        ];

        foreach ($roles as $role) {
            VolunteerRole::create([
                'event_id' => $event->id,
                'name' => $role['name'],
                'max_slots' => $role['max_slots'],
            ]);
        }
    }
}
