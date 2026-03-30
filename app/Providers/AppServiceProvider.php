<?php

namespace App\Providers;

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
        // Fix for Livewire temporary uploads on local environments
        if (config('app.env') === 'local') {
            \Illuminate\Support\Facades\URL::forceRootUrl(config('app.url'));
        }
        
        // Ensure storage directory is writable and link exists
        if (!file_exists(storage_path('app/livewire-tmp'))) {
            @mkdir(storage_path('app/livewire-tmp'), 0775, true);
        }
    }
}
