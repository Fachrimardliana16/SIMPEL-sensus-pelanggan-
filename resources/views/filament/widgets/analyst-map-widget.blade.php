<x-filament-widgets::widget>
    <x-filament::section>
        <div class="flex items-center justify-between gap-x-3 mb-4">
            <div>
                <h3 class="text-lg font-bold text-gray-950 dark:text-white">
                    🗺️ Peta Persebaran Sensus Valid
                </h3>
                <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                    Menampilkan <strong>{{ count($locations) }}</strong> titik sensus valid di area cakupan survei.
                </p>
            </div>
        </div>

        <div class="relative w-full rounded-xl overflow-hidden shadow-inner bg-gray-100 dark:bg-gray-800"
             style="height: 460px;"
             wire:ignore
             x-data
             x-init="window.initLeafletMap('analyst-map-container', {{ Js::from($locations) }}, {{ $mapCenterLat }}, {{ $mapCenterLng }}, false)">
            <div id="analyst-map-container" style="width:100%;height:100%;"></div>

            @if(count($locations) === 0)
                <div class="absolute inset-0 flex flex-col items-center justify-center gap-2 text-gray-400 pointer-events-none z-10">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-12 h-12 opacity-30" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1 1 15 0Z"/>
                    </svg>
                    <p class="text-sm font-medium">Belum ada data sensus valid dengan koordinat GPS.</p>
                </div>
            @endif
        </div>
    </x-filament::section>
</x-filament-widgets::widget>
