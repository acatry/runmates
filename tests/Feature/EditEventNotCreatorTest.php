<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Event;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class EditEventNotCreatorTest extends TestCase
{
    use RefreshDatabase;

    public function test_cannot_edit_event()
    {
        $organizerA = User::factory()->create(['role' => 'organizer']);
        $organizerB = User::factory()->create(['role' => 'organizer']);
        $event = Event::factory()->create([
            'organizer_id' => $organizerA->id
        ]);
        $response = $this->actingAs($organizerB)
                         ->get("/events/{$event->id}/edit");
        $response->assertStatus(403);
    }
}