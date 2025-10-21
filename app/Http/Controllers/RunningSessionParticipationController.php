<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\RunningSession;
use App\Models\RunningSessionParticipation;

class RunningSessionParticipationController extends Controller
{
    public function store(Request $request, RunningSession $runningSession)
    {
        $alreadyParticipating = RunningSessionParticipation::where('user_id', $request->user()->id)
            ->where('running_session_id', $runningSession->id)
            ->exists();

        if (!$alreadyParticipating) {
            $participation = new RunningSessionParticipation();
            $participation->user_id = $request->user()->id;
            $participation->running_session_id = $runningSession->id;
            $participation->status = 'confirmed';
            $participation->save();

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
            return redirect()->back()->with('success', 'Vous vous êtes désinscrit de cette session.');
        } else {
            return redirect()->back()->with('success', 'Vous n’étiez pas inscrit à cette session.');
        }
    }
}
