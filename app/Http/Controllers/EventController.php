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

        if ($request->start_at < date('Y-m-d')) {
            return back()->withErrors(['start_at' => "La date ne peut pas être antérieure à celle d'aujourd'hui."]);
        }


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
    public function index(Request $request)
    {
        $keyword = $request->input('q');
        $onlyVolunteers = $request->only_volunteers == '1';

        $query = Event::orderBy('start_at');

        if ($keyword) {
            $query->where(function ($subQuery) use ($keyword) {
                $subQuery->where('title', 'like', "%$keyword%")
                  ->orWhere('description', 'like', "%$keyword%")
                  ->orWhere('location', 'like', "%$keyword%");
            });
        }

        if ($onlyVolunteers) {
            $query->where('volunteers_needed', true);
        }

        $events = $query->paginate(10)->withQueryString();
        return view('events.index', compact('events', 'keyword', 'onlyVolunteers'));
    }

}
