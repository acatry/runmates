<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;


class DashboardController extends Controller
{
    public function dashboard()
    {
        $user = auth()->user();

        $events = $user->attendingEvents()
            ->where('start_at', '>', now())
            ->orderBy('start_at')
            ->get();

        $sessions = $user->runningSessionsJoined()
            ->where('start_at', '>', now())
            ->orderBy('start_at')
            ->get();

        return view('dashboard', compact('events', 'sessions'));
    }
}
