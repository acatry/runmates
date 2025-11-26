<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Event;
use App\Models\VolunteerRole;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class JoinVolunteerTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_volunteer()
    {
        $user = User::factory()->create(['role' => 'sporty']);
        $event = Event::factory()->create();
        $role = VolunteerRole::factory()->create([
            'event_id' => $event->id,
            'max_slots' => 10,
        ]);

        $response = $this->actingAs($user)->post(route('events.volunteers.store', $event),['volunteer_role_id' => $role->id]
        );

        $response->assertRedirect();
        $this->assertDatabaseHas('event_volunteers', [
            'event_id' => $event->id,
            'user_id' => $user->id,
            'volunteer_role_id' => $role->id,
        ]);
    }
    public function test_can_not_volunteer_twice()
    {
        $user = User::factory()->create(['role' => 'sporty']);
        $event = Event::factory()->create();
        $role = VolunteerRole::factory()->create([
            'event_id' => $event->id,
            'max_slots' => 10,
        ]);

        $response = $this->actingAs($user)->post(route('events.volunteers.store', $event),['volunteer_role_id' => $role->id]
        );

        $response = $this->actingAs($user)->post(route('events.volunteers.store', $event),['volunteer_role_id' => $role->id]
        );

        $response->assertSessionHas('error');
        $response->assertRedirect();

        $this->assertDatabaseCount('event_volunteers', 1);
    }
}