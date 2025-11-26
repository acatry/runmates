<?php

namespace Database\Factories;

use App\Models\Event;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class EventFactory extends Factory
{
    protected $model = Event::class;

    public function definition()
    {
        return [
            'organizer_id' => User::factory()->create(['role' => 'organizer']),
            'title' => $this->faker->sentence(1),
            'description' => $this->faker->sentence(1),
            'location' => $this->faker->city(),
            'start_at' => now()->addDays(2),
            'volunteers_needed' => '1',
        ];
    }
}