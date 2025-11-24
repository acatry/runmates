<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Event;
use App\Models\VolunteerRole;
use Illuminate\Support\Facades\DB;

class EventVolunteerController extends Controller
{
    public function store(Request $request, Event $event)
    {
        $request->validate([
            'volunteer_role_id' => ['required', 'integer'],
        ]);

        $user = $request->user();

        if (! $user->isSporty()) {
            abort(403);
        }

        $count = DB::table('event_volunteers')
            ->where('event_id', $event->id)
            ->where('volunteer_role_id', $request->volunteer_role_id)
            ->count();

        $max = VolunteerRole::find($request->volunteer_role_id)->max_slots;

        if ($max && $count >= $max) {
            return back()->with('error', 'Ce poste est complet.');
        }

        DB::table('event_volunteers')->updateOrInsert(
            [
                'event_id' => $event->id,
                'user_id'  => $user->id,
            ],
            [
                'volunteer_role_id' => $request->volunteer_role_id,
                'created_at'        => now(),
                'updated_at'        => now(),
            ]
        );

        return back()->with('success', 'Vous vous êtes inscrit en tant que bénévole pour cet évènement.');
    }

    public function delete(Request $request, Event $event)
    {
        $user = $request->user();

        DB::table('event_volunteers')
            ->where('event_id', $event->id)
            ->where('user_id', $user->id)
            ->delete();

        return back()->with('success', 'Vous n’êtes plus inscrit comme bénévole pour cet événement.');
    }
}
