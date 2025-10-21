<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\EventController;
use App\Http\Controllers\EventRegistrationController;
use App\Http\Controllers\RunningSessionController;
use App\Http\Controllers\RunningSessionParticipationController;



Route::get('/', function () {
    if (auth()->check()) {
        return redirect()->route('events.index'); 
    }
    return view('home'); 
})->name('home');


Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    //EVENTS
    Route::get('/events/create', [EventController::class, 'create'])->name('events.create');
    Route::post('/events', [EventController::class, 'store'])->name('events.store');
    Route::get('/events', [EventController::class, 'index'])->name('events.index');
    Route::get('/events/{event}', [EventController::class, 'show'])->name('events.show');
    Route::post('/events/{event}/register', [EventRegistrationController::class, 'store'])
        ->name('events.register');
    Route::delete('/events/{event}/register', [EventRegistrationController::class, 'destroy'])
        ->name('events.unregister');

    //RUNNING SESSION
    Route::get('/running-sessions', [RunningSessionController::class, 'index'])->name('running-sessions.index');
    Route::get('/running-sessions/create', [RunningSessionController::class, 'create'])->name('running-sessions.create');
    Route::post('/running-sessions', [RunningSessionController::class, 'store'])->name('running-sessions.store');
    Route::get('/running-sessions/{runningSession}', [RunningSessionController::class, 'show'])->name('running-sessions.show');
    Route::post('/running-sessions/{runningSession}/join', [RunningSessionParticipationController::class, 'store'])
        ->name('running-sessions.join');
    Route::delete('/running-sessions/{runningSession}/join', [RunningSessionParticipationController::class, 'destroy'])
        ->name('running-sessions.leave');


    //PROFILE
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
