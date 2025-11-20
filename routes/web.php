<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\EventRegistrationController;
use App\Http\Controllers\RunningSessionController;
use App\Http\Controllers\RunningSessionParticipationController;
use \App\Http\Controllers\PublicProfileController;
use \App\Http\Controllers\CommentController;
use App\Http\Controllers\EventVolunteerController;
use App\Http\Controllers\NotificationController;

Route::get('/', function () {
    if (auth()->check()) {
        return redirect()->route('events.index'); 
    }
    return view('home'); 
})->name('home');


Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'dashboard'])
        ->name('dashboard');
    //EVENTS + GATE LA OU C'EST NECESSAIRE
    Route::get('/events/create', [EventController::class, 'create'])->name('events.create')->can('event-create');
    Route::post('/events', [EventController::class, 'store'])->name('events.store')->can('event-create');
    Route::get('/events', [EventController::class, 'index'])->name('events.index');
    Route::get('/events/{event}', [EventController::class, 'show'])->name('events.show');
    Route::post('/events/{event}/register', [EventRegistrationController::class, 'store'])
        ->name('events.register');
    Route::delete('/events/{event}/register', [EventRegistrationController::class, 'destroy'])
        ->name('events.unregister');
    //EDIT + DELETE + GATE
    Route::get('/events/{event}/edit', [EventController::class, 'edit'])->name('events.edit')->can('event-update', 'event');
    Route::put('/events/{event}', [EventController::class, 'update'])->name('events.update')->can('event-update', 'event');
    Route::delete('/events/{event}', [EventController::class, 'delete'])->name('events.delete')->can('event-delete', 'event');

    //RUNNING SESSION + GATE LA OU C'EST NECESSAIRE
    Route::get('/running-sessions', [RunningSessionController::class, 'index'])->name('running-sessions.index')->can('session-create');
    Route::get('/running-sessions/create', [RunningSessionController::class, 'create'])->name('running-sessions.create')->can('session-create');
    Route::post('/running-sessions', [RunningSessionController::class, 'store'])->name('running-sessions.store');
    Route::get('/running-sessions/{runningSession}', [RunningSessionController::class, 'show'])->name('running-sessions.show');
    Route::post('/running-sessions/{runningSession}/join', [RunningSessionParticipationController::class, 'store'])->name('running-sessions.join');
    Route::delete('/running-sessions/{runningSession}/join', [RunningSessionParticipationController::class, 'destroy'])->name('running-sessions.leave');
    //EDIT + DELETE + GATE
    Route::get('/running-sessions/{runningSession}/edit', [RunningSessionController::class, 'edit'])->name('running-sessions.edit')->can('session-update', 'runningSession');
    Route::put('/running-sessions/{runningSession}', [RunningSessionController::class, 'update'])->name('running-sessions.update')->can('session-update', 'runningSession');
    Route::delete('/running-sessions/{runningSession}', [RunningSessionController::class, 'delete'])->name('running-sessions.delete')->can('session-delete', 'runningSession');

    //VUE NOTIFICATIONS
    Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications.index');


    //PROFILE
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::get('/runner/{user}', [PublicProfileController::class, 'show'])->name('runner.profile');

    //COMMENTS
    Route::post('/comments', [CommentController::class, 'store'])->name('comments.store');

    // BÉNÉVOLES SUR LES ÉVÈNEMENTS
    Route::post('/events/{event}/volunteers', [EventVolunteerController::class, 'store'])->name('events.volunteers.store');
    Route::delete('/events/{event}/volunteers', [EventVolunteerController::class, 'delete'])->name('events.volunteers.delete');

});

require __DIR__.'/auth.php';
