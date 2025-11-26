<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\RunningSession;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CreateRunningSessionTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_create_running_session()
    {
        $sporty = User::factory()->create(['role' => 'sporty']);

        $response = $this->actingAs($sporty)->post('/running-sessions', [
            'title'       => 'Session test',
            'description' => 'Entraînement du dimanche matin',
            'location'    => 'Parc de mouscron',
            'city'        => 'Mouscron',
            'zipcode'     => '7700',
            'start_at'    => now()->addDays(3),
        ]);

        $response->assertRedirect(route('running-sessions.index'));

        $this->assertDatabaseHas('running_sessions', [
            'title' => 'Session test',
            'organizer_id' => $sporty->id,
        ]);
    }

    public function test_cannot_create_with_past_date()
    {
        $sporty = User::factory()->create(['role' => 'sporty']);

        $response = $this->actingAs($sporty)->post('/running-sessions', [
            'title'       => 'Test mauvaise date',
            'description' => 'Sprint',
            'location'    => 'Parc de mouscron',
            'city'        => 'Mouscron',
            'zipcode'     => '7700',
            'start_at'    => now()->subDays(2),
        ]);

        $response->assertSessionHasErrors('start_at');

        $this->assertDatabaseMissing('running_sessions', [
            'title' => 'Test mauvaise date'
        ]);
    }
}