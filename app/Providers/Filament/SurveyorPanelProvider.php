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

class SurveyorPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->id('surveyor')
            ->path('surveyor')
            ->renderHook('panels::head.end', fn () => '
                <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
                <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
                <script>
                    if (!window.initLeafletMap) {
                        window.initLeafletMap = function(containerId, locations, centerLat, centerLng, useColorIcons) {
                            var _colorMap = { green: "#22c55e", orange: "#f97316", red: "#ef4444" };
                            function _makeIcon(color) {
                                var fill = _colorMap[color] || "#3b82f6";
                                var svg = "<svg xmlns=\'http://www.w3.org/2000/svg\' viewBox=\'0 0 24 24\' width=\'32\' height=\'32\'>"
                                        + "<path fill=\'" + fill + "\' stroke=\'white\' stroke-width=\'1.5\' "
                                        + "d=\'M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7z"
                                        + "m0 9.5c-1.38 0-2.5-1.12-2.5-2.5s1.12-2.5 2.5-2.5 2.5 1.12 2.5 2.5-1.12 2.5-2.5 2.5z\'/>"
                                        + "</svg>";
                                return L.divIcon({ html: svg, className: "", iconSize: [32,32], iconAnchor: [16,32], popupAnchor: [0,-32] });
                            }
                            function _boot() {
                                if (typeof L === "undefined") { setTimeout(_boot, 200); return; }
                                var el = document.getElementById(containerId);
                                if (!el) { setTimeout(_boot, 200); return; }
                                if (el._leaflet_id) { el._leaflet_id = null; el.innerHTML = ""; }
                                var map = L.map(el, { zoomControl: true }).setView([centerLat, centerLng], 12);
                                L.tileLayer("https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png", {
                                    attribution: "© OpenStreetMap", maxZoom: 18
                                }).addTo(map);
                                var bounds = [];
                                (locations || []).forEach(function(loc) {
                                    var lat = parseFloat(loc.lat), lng = parseFloat(loc.lng);
                                    if (!isNaN(lat) && !isNaN(lng) && lat !== 0 && lng !== 0) {
                                        var opts = useColorIcons ? { icon: _makeIcon(loc.color) } : {};
                                        var popup = "<strong>" + loc.title + "</strong>";
                                        if (loc.status) popup += "<br><small>Status: " + loc.status + "</small>";
                                        L.marker([lat, lng], opts).addTo(map).bindPopup(popup);
                                        bounds.push([lat, lng]);
                                    }
                                });
                                if (bounds.length > 0) { map.fitBounds(bounds, { padding: [40, 40] }); }
                                setTimeout(function() { map.invalidateSize(); }, 500);
                            }
                            _boot();
                        };
                    }

                    /* Filterable map — markers grouped by status into LayerGroups */
                    if (!window.initFilterableMap) {
                        window._mapRegistry = {};
                        window.initFilterableMap = function(containerId, locations, centerLat, centerLng) {
                            var _colorMap = { green: "#22c55e", orange: "#f97316", red: "#ef4444" };
                            var _statusToColor = { valid: "green", pending: "orange", revisi: "red" };
                            function _makeIcon(color) {
                                var fill = _colorMap[color] || "#3b82f6";
                                var svg = "<svg xmlns=\'http://www.w3.org/2000/svg\' viewBox=\'0 0 24 24\' width=\'32\' height=\'32\'>"
                                        + "<path fill=\'" + fill + "\' stroke=\'white\' stroke-width=\'1.5\' "
                                        + "d=\'M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7z"
                                        + "m0 9.5c-1.38 0-2.5-1.12-2.5-2.5s1.12-2.5 2.5-2.5 2.5 1.12 2.5 2.5-1.12 2.5-2.5 2.5z\'/>"
                                        + "</svg>";
                                return L.divIcon({ html: svg, className: "", iconSize: [32,32], iconAnchor: [16,32], popupAnchor: [0,-32] });
                            }
                            function _boot() {
                                if (typeof L === "undefined") { setTimeout(_boot, 200); return; }
                                var el = document.getElementById(containerId);
                                if (!el) { setTimeout(_boot, 200); return; }
                                if (el._leaflet_id) { el._leaflet_id = null; el.innerHTML = ""; }
                                var map = L.map(el, { zoomControl: true }).setView([centerLat, centerLng], 12);
                                L.tileLayer("https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png", {
                                    attribution: "© OpenStreetMap", maxZoom: 18
                                }).addTo(map);
                                var layers = { valid: L.layerGroup().addTo(map), pending: L.layerGroup().addTo(map), revisi: L.layerGroup().addTo(map) };
                                var bounds = [];
                                (locations || []).forEach(function(loc) {
                                    var lat = parseFloat(loc.lat), lng = parseFloat(loc.lng);
                                    if (!isNaN(lat) && !isNaN(lng) && lat !== 0 && lng !== 0) {
                                        var status = loc.status || "pending";
                                        var color = _statusToColor[status] || "orange";
                                        var marker = L.marker([lat, lng], { icon: _makeIcon(color) })
                                            .bindPopup("<strong>" + loc.title + "</strong><br><small>Status: " + status + "</small>");
                                        if (layers[status]) { marker.addTo(layers[status]); }
                                        else { marker.addTo(layers.pending); }
                                        bounds.push([lat, lng]);
                                    }
                                });
                                if (bounds.length > 0) { map.fitBounds(bounds, { padding: [40, 40] }); }
                                setTimeout(function() { map.invalidateSize(); }, 500);
                                window._mapRegistry[containerId] = { map: map, layers: layers };
                            }
                            _boot();
                        };
                        window.toggleMapLayer = function(containerId, status) {
                            var reg = window._mapRegistry[containerId];
                            if (!reg || !reg.layers[status]) return;
                            var map = reg.map;
                            var layer = reg.layers[status];
                            if (map.hasLayer(layer)) { map.removeLayer(layer); }
                            else { map.addLayer(layer); }
                        };
                    }
                </script>
            ')
            ->colors([
                'primary' => Color::Blue,
                'gray' => Color::Slate,
            ])
            ->font('Inter')
            ->brandName('SIMPEL Surveyor')
            ->sidebarCollapsibleOnDesktop()
            ->discoverResources(in: app_path('Filament/Surveyor/Resources'), for: 'App\\Filament\\Surveyor\\Resources')
            ->discoverPages(in: app_path('Filament/Surveyor/Pages'), for: 'App\\Filament\\Surveyor\\Pages')
            ->pages([
                Pages\Dashboard::class,
            ])
            ->databaseNotifications()
            ->profile()
            // ->discoverWidgets(in: app_path('Filament/Surveyor/Widgets'), for: 'App\\Filament\\Surveyor\\Widgets')
            ->widgets([
                // Quick actions — 2 cols side by side
                \App\Filament\Widgets\SurveyorQuickInputWidget::class,
                \App\Filament\Widgets\SurveyorProfileWidget::class,
                // Stats
                \App\Filament\Widgets\SurveyorStats::class,
                \App\Filament\Widgets\MySubmissionTrendChart::class,
                \App\Filament\Widgets\RecentSensusTable::class,
                // Map — full width at bottom
                \App\Filament\Widgets\SurveyorMapWidget::class,
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
