<?php

namespace App\Providers;
use App\Models\Event;
use App\Models\RunningSession;
use Illuminate\Support\Facades\Gate;


use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Gate::define('event-create', function ($user) {
            return $user->isOrganizer();
        });

        Gate::define('event-update', function ($user, Event $event) {
            return $user->isOrganizer() && $user->id === $event->organizer_id;
        });

        Gate::define('event-delete', function ($user, Event $event) {
            return $user->isOrganizer() && $user->id === $event->organizer_id;
        });

        Gate::define('session-create', function ($user) {
            return $user->isSporty();
        });

        Gate::define('session-update', function ($user, RunningSession $session) {
            return $user->isSporty() && $user->id === $session->organizer_id;
        });

        Gate::define('session-delete', function ($user, RunningSession $session) {
            return $user->isSporty() && $user->id === $session->organizer_id;
        });
    }
}