<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\EventRegistration;
use Illuminate\Http\Request;

class EventRegistrationController extends Controller
{
    public function store(Request $request, Event $event)
    {
        if ($event->start_at-> lt(now())){
            return back()->with('error','Impossible de sʼinscrire à un évènement déjà passé.');
        }
        // (Plus tard: vérif deadline/capacité)
        EventRegistration::firstOrCreate([
            'user_id' => $request->user()->id,
            'event_id' => $event->id,
        ]);

        return back()->with('success', 'Inscription confirmée !');
    }

    public function destroy(Request $request, Event $event)
    {
        EventRegistration::where('user_id', $request->user()->id)
            ->where('event_id', $event->id)
            ->delete();

        return back()->with('success', 'Désinscription effectuée.');
    }
}
