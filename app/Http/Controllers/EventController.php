<?php

namespace App\Http\Controllers;

use App\Models\Event;
use Illuminate\Http\Request;

class EventController extends Controller
{
    public function create()
    {
        return view('events.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'location' => 'required|string|max:255',
            'start_at' => 'required|date',
        ]);

        $validated['organizer_id'] = auth()->id();

        Event::create($validated);

        return redirect()->route('events.index')->with('success', 'Événement créé !');
    }

    public function index()
    {
        $events = \App\Models\Event::orderBy('start_at')->paginate(10);
        return view('events.index', compact('events'));
    }

    public function show(\App\Models\Event $event)
    {
        return view('events.show', compact('event'));
    }
}
