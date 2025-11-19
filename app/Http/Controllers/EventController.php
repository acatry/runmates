<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\VolunteerRole;
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
            'volunteers_needed' => 'nullable|in:0,1',
            'roles.*' => 'nullable|string',
            'max.*'   => 'nullable|integer|min:1',
        ]);

        $validated['organizer_id'] = auth()->id();

        $validated['volunteers_needed'] = (int)($request->input('volunteers_needed', 0));

        $event = Event::create($validated);

        if ($validated['volunteers_needed'] === 1) {
            $roles = $request->input('roles', []); 
            $max   = $request->input('max', []);         
            foreach ($roles as $index => $roleName) {
                if (!empty($max[$index])) {
                    $maxSlots = (int) $max[$index];

                    VolunteerRole::create([
                        'event_id'  => $event->id,
                        'name'      => $roleName,
                        'max_slots' => $maxSlots,
                    ]);
                }
            }
        }

        return redirect()->route('events.index')->with('success', 'Événement créé !');
    }

    public function index(Request $request)
    {
        $search = $request->q;
        $onlyVolunteers = $request->only_volunteers == '1';

        $query = Event::orderBy('start_at');

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%$search%")
                  ->orWhere('description', 'like', "%$search%")
                  ->orWhere('location', 'like', "%$search%");
            });
        }

        if ($onlyVolunteers) {
            $query->where('volunteers_needed', true);
        }

        $events = $query->paginate(10)->withQueryString();
        return view('events.index', compact('events', 'search', 'onlyVolunteers'));
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

        return redirect()->route('events.index')
            ->with('success', 'L évènement a été supprimé avec succès.');
    }

}
