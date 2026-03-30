<?php

namespace App\Providers;

use Filament\View\PanelsRenderHook;
use Filament\Support\Facades\FilamentView;
use Illuminate\Support\Facades\Blade;
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
        FilamentView::registerRenderHook(
            PanelsRenderHook::SIDEBAR_FOOTER,
            fn (): string => Blade::render('
                <div class="px-6 py-4 border-t border-gray-100 dark:border-white/5 transition-all duration-300 overflow-hidden">
                    <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest text-center flex flex-col items-center justify-center">
                        <span x-show="$store.sidebar.isOpen" class="mb-1">Created by v1.0.2-stable</span>
                        <a href="https://chillfile.my.id/" target="_blank" class="text-blue-600 hover:text-blue-700 transition-colors">
                            <span x-show="$store.sidebar.isOpen">fachrimardliana</span>
                            <span x-show="!$store.sidebar.isOpen" class="text-[9px] font-black">FM v1.0</span>
                        </a>
                    </p>
                </div>
            '),
        );
    }
}
