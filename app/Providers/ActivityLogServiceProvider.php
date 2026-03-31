<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Event;
use Illuminate\Auth\Events\Login;
use Illuminate\Auth\Events\Logout;
use Spatie\Activitylog\Models\Activity;

class ActivityLogServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        Event::listen(Login::class, function ($event) {
            activity('auth')
                ->causedBy($event->user)
                ->log('User logged in');
        });

        Event::listen(Logout::class, function ($event) {
            activity('auth')
                ->causedBy($event->user)
                ->log('User logged out');
        });
    }
}
