<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\RunningSession;
use App\Models\RunningSessionParticipation;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class JoinRunningSessionTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_join_a_running_session()
    {
        $user = User::factory()->create(['role' => 'sporty']);
        $session = RunningSession::factory()->create();

        $response = $this->actingAs($user)->post("/running-sessions/{$session->id}/join");

        $response->assertSessionHas('success');

        $this->assertDatabaseHas('running_session_participations', [
            'user_id' => $user->id,
            'running_session_id' => $session->id,
        ]);
    }

    public function test_cannot_join_twice()
    {
        $user = User::factory()->create(['role' => 'sporty']);
        $session = RunningSession::factory()->create();

        $response = $this->actingAs($user)->post("/running-sessions/{$session->id}/join");

        $response = $this->actingAs($user)->post("/running-sessions/{$session->id}/join");

        $response->assertSessionHas('success', 'Vous participez déjà à cette session.');

        $this->assertDatabaseCount('running_session_participations', 1);
    }
    
    public function test_cannot_join_past_running_session()
    {
        $user = User::factory()->create(['role' => 'sporty']);
        $session = RunningSession::factory()->create(['start_at' => now()->subDays(2),]);

        $response = $this->actingAs($user)->post("/running-sessions/{$session->id}/join");

        $response->assertSessionHas('error', 'Impossible de s’inscrire à une session d’entraînement déjà passée.');

        $this->assertDatabaseMissing('running_session_participations', [
            'user_id' => $user->id,
            'running_session_id' => $session->id,
        ]);
    }
}

