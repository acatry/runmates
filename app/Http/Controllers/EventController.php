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
        $events = Event::orderBy('start_at')->paginate(10);
        return view('events.index', compact('events'));
    }

    public function show(Event $event)
    {
        return view('events.show', compact('event'));
    }

    public function edit(Request $request, Event $event)
    {
        if ($request->user()->id !== $event->organizer_id) {
            abort(403);
        }

        return view('events.edit', compact('event'));
    }

    public function update(Request $request, Event $event)
    {
        if ($request->user()->id !== $event->organizer_id) {
            abort(403);
        }

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'location' => 'required|string|max:255',
            'start_at' => 'required|date',
        ]);

        $event->update($validated);

        return redirect()
            ->route('events.show', $event)
            ->with('success', 'Événement mis à jour.');
    }

    public function delete(Request $request, Event $event)
    {
        if ($request->user()->id !== $event->organizer_id) {
            abort(403);
        }

        $event->delete();

        return redirect()->route('running-sessions.index')
            ->with('success', 'L évènement a été supprimé avec succès.');
    }

}
