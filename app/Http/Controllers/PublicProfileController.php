<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class PublicProfileController extends Controller
{
    public function show(User $user)
    {
        $futureSessions = [];
        $futureEvents = [];

        if ($user->isSporty()) {
            $futureSessions = $user->runningSessionsJoined()
                ->where('start_at', '>', now())
                ->orderBy('start_at')
                ->get();
        }

        if ($user->isOrganizer()) {
            $futureEvents = $user->eventsOrganized()
                ->orderBy('start_at')
                ->get();
        }

        return view('public-profile.show', compact('user', 'futureSessions', 'futureEvents'));
    }
}
