<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CreateEventTest extends TestCase
{
    use RefreshDatabase;

    public function test_organizer_can_create_event()
    {
        $organizer = User::factory()->create(['role' => 'organizer']);

        $response = $this->actingAs($organizer)->post(route('events.store'), [
            'title' => 'Evenement test',
            'description' => 'Description',
            'location' => 'Parc',
            'start_at' => now()->addDays(5),
            'volunteers_needed' => 1,
        ]);

        $response->assertRedirect(route('events.index'));

        $this->assertDatabaseHas('events', [
            'title' => 'Evenement test',
            'organizer_id' => $organizer->id,
        ]);
    }

    public function test_sporty_cannot_create_event()
    {
        $sporty = User::factory()->create(['role' => 'sporty']);

        $response = $this->actingAs($sporty)->post(route('events.store'), [
            'title' => 'Evenement test',
            'description' => 'Description',
            'location' => 'Parc',
            'start_at' => now()->addDays(5),
        ]);

        $response->assertStatus(403);
    }
}