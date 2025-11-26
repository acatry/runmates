<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use app\Models\User;
use app\Models\RunningSession;

class RunningSessionFactory extends Factory
{
    protected $model = RunningSession::class;

    public function definition(): array
    {
        return [
            'organizer_id' => USer::factory(),
            'title' => $this->faker->sentence(1),
            'description' => $this->faker->sentence(1),
            'location' => $this->faker->sentence(1),
            'city' => $this->faker->city(),
            'zipcode' => $this->faker->postcode(),
            'start_at' => now()->addDays(1),
        ];
    }
}
