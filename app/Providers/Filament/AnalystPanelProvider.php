<?php

namespace App\Providers\Filament;

use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\AuthenticateSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Pages;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Filament\Widgets;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;

class AnalystPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->id('analyst')
            ->path('analyst')
            ->colors([
                'primary' => Color::Blue,
                'gray' => Color::Slate,
            ])
            ->font('Inter')
            ->brandName('SIMPEL Analyst')
            ->sidebarCollapsibleOnDesktop()
            ->discoverResources(in: app_path('Filament/Analyst/Resources'), for: 'App\\Filament\\Analyst\\Resources')
            ->discoverPages(in: app_path('Filament/Analyst/Pages'), for: 'App\\Filament\\Analyst\\Pages')
            ->pages([
                Pages\Dashboard::class,
            ])
            // ->discoverWidgets(in: app_path('Filament/Analyst/Widgets'), for: 'App\\Filament\\Analyst\\Widgets')
            ->widgets([
                \App\Filament\Widgets\QuickAccessWidget::class,
                \App\Filament\Widgets\PerformanceWidget::class,
                \App\Filament\Widgets\AnalystStats::class,
                \App\Filament\Widgets\SurveyorPerformanceChart::class,
                \App\Filament\Widgets\CensusStatusPieChart::class,
                \App\Filament\Widgets\LatestSensusWidget::class,
            ])
            ->middleware([
                EncryptCookies::class,
                AddQueuedCookiesToResponse::class,
                StartSession::class,
                AuthenticateSession::class,
                ShareErrorsFromSession::class,
                VerifyCsrfToken::class,
                SubstituteBindings::class,
                DisableBladeIconComponents::class,
                DispatchServingFilamentEvent::class,
            ])
            ->authMiddleware([
                Authenticate::class,
            ]);
    }
}
