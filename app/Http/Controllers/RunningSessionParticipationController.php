<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\RunningSession;
use App\Models\RunningSessionParticipation;
use App\Models\Notification;

class RunningSessionParticipationController extends Controller
{
    public function store(Request $request, RunningSession $runningSession)
    {
        if ($runningSession->start_at->isPast()) {
            return back()->with('error', 'Impossible de s’inscrire à une session d’entraînement déjà passée.');
        }

        $alreadyParticipating = RunningSessionParticipation::where('user_id', $request->user()->id)
            ->where('running_session_id', $runningSession->id)
            ->exists();

        if (!$alreadyParticipating) {
            $participation = new RunningSessionParticipation();
            $participation->user_id = $request->user()->id;
            $participation->running_session_id = $runningSession->id;
            $participation->save();

            if ($runningSession->organizer_id !== $request->user()->id) {
                Notification::create([
                    'user_id' => $runningSession->organizer_id,
                    'sender_id'  => $request->user()->id,
                    'type' => 'join_running_session',
                    'message' => $request->user()->name.' a rejoint votre running session "'.$runningSession->title.'".',
                    'related_id' => $runningSession->id,
                ]);
            }

            return redirect()->back()->with('success', 'Vous avez rejoint cette session d’entraînement.');
        } else {
            return redirect()->back()->with('success', 'Vous participez déjà à cette session.');
        }
    }

    public function destroy(Request $request, RunningSession $runningSession)
    {
        $participation = RunningSessionParticipation::where('user_id', $request->user()->id)
            ->where('running_session_id', $runningSession->id)
            ->first();

        if ($participation) {
            $participation->delete();
            Notification::where('user_id', $runningSession->organizer_id)
                ->where('sender_id', $request->user()->id)
                ->where('type', 'join_running_session')
                ->where('related_id', $runningSession->id)
                ->delete();
            return redirect()->back()->with('success', 'Vous vous êtes désinscrit de cette session.');
        } else {
            return redirect()->back()->with('success', 'Vous n’étiez pas inscrit à cette session.');
        }
    }
}
