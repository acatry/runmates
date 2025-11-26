<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\User;
use App\Models\VolunteerRole;
use App\Models\Event;

class VolunteerRoleFactory extends Factory
{
    protected $model = VolunteerRole::class;

    public function definition(): array
    {
        return [
            'event_id' => Event::factory(),
            'name' => $this->faker->randomElement([
                'Ravitaillement',
                'Signaleur',
                'Ouverture / fermeture de course',
                'Distribution de dossards',
                'Sécurité du parcours',
                'Photographe'
            ]),
            'max_slots' => $this->faker->numberBetween(1,20),
        ];
    }
}
