<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\RunningSession;

class RunningSessionController extends Controller
{
    public function create()
    {
        return view('running-sessions.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'location' => 'required|string|max:255',
            'city' => 'nullable|string|max:120',
            'zipcode' => 'nullable|string|max:12',
            'start_at' => 'required|date',
            'distance_km_min' => 'nullable|numeric|min:0.1|max:500',
            'distance_km_max' => 'nullable|numeric|min:0.1|max:500',
            'pace_min_per_km_min' => 'nullable|numeric|min:2|max:15',
            'pace_min_per_km_max' => 'nullable|numeric|min:2|max:15',
            'duration_min' => 'nullable|integer|min:5|max:1000',
            'duration_max' => 'nullable|integer|min:5|max:1000',
            'max_participants' => 'nullable|integer|min:1|max:500',
            'latitude'  => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
        ]);

        if ($request->start_at < date('Y-m-d')) {
            return back()->withErrors(['start_at' => "La date ne peut pas être antérieure à aujourd'hui."]);
        }


        $validated['organizer_id'] = auth()->id();

        RunningSession::create($validated);

        return redirect()->route('running-sessions.index')
                         ->with('success', 'Session d’entraînement créée!');
    }

    public function show(RunningSession $runningSession)
    {
        return view('running-sessions.show', [
            'session' => $runningSession
        ]);
    }


    public function edit(Request $request, RunningSession $runningSession)
    {
        if ($request->user()->id !== $runningSession->organizer_id) {
            abort(403);
        }

        return view('running-sessions.edit', ['session' => $runningSession]);
    }
    public function update(Request $request, RunningSession $runningSession)
    {
        if ($request->user()->id !== $runningSession->organizer_id) {
            abort(403);
        }

        $data = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'location' => 'required|string|max:255',
            'city' => 'nullable|string|max:120',
            'zipcode' => 'nullable|string|max:12',
            'start_at' => 'required|date',
            'distance_km_min' => 'nullable|numeric|min:0.1|max:500',
            'distance_km_max' => 'nullable|numeric|min:0.1|max:500',
            'pace_min_per_km_min' => 'nullable|numeric|min:2|max:15',
            'pace_min_per_km_max' => 'nullable|numeric|min:2|max:15',
            'duration_min' => 'nullable|integer|min:5|max:1000',
            'duration_max' => 'nullable|integer|min:5|max:1000',
            'max_participants' => 'nullable|integer|min:1|max:500',
            'latitude'  => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
        ]);

        $runningSession->update($data);

        return redirect()->route('running-sessions.show', $runningSession)
            ->with('success', 'La session a été mise à jour');
    }

    public function delete(Request $request, RunningSession $runningSession)
    {
        if ($request->user()->id !== $runningSession->organizer_id) {
            abort(403);
        }

        $runningSession->delete();

        return redirect()->route('running-sessions.index')
            ->with('success', 'La session a été supprimée avec succès.');
    }

    // Affiche la liste de toutes les sessions d'entraînement
    public function index(Request $request)
    {
        
        $keyword = $request->input('q');       
        $city = $request->input('city');       
        $zipcode = $request->input('zipcode'); 

        $query = RunningSession::where('start_at', '>', now())
                                ->orderBy('start_at');

        if (!empty($keyword)) {
            $query->where(function($subQuery) use ($keyword) {
                $subQuery->where('title', 'like', "%{$keyword}%")
                         ->orWhere('location', 'like', "%{$keyword}%")
                         ->orWhere('city', 'like', "%{$keyword}%");
            });
        }

        if (!empty($city)) {
            $query->where('city', 'like', "%{$city}%");
        }
        if (!empty($zipcode)) {
            $query->where('zipcode', 'like', "%{$zipcode}%");
        }

        $sessions = $query->paginate(10);

        return view('running-sessions.index', [
            'sessions' => $sessions
        ]);
    }

}
