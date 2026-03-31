<x-filament-widgets::widget>
    <x-filament::section>
        <div class="flex items-center justify-between gap-x-3 mb-4">
            <div>
                <h3 class="text-lg font-bold text-gray-950 dark:text-white">
                    🗺️ Peta Kiriman Sensus Saya
                </h3>
                <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                    Menampilkan <strong>{{ count($locations) }}</strong> titik sensus Anda di area cakupan survei.
                </p>
            </div>

            {{-- Filter buttons --}}
            <div class="flex items-center gap-2">
                @php
                    $validCount   = collect($locations)->where('status', 'valid')->count();
                    $pendingCount = collect($locations)->where('status', 'pending')->count();
                    $revisiCount  = collect($locations)->where('status', 'revisi')->count();
                @endphp

                <button type="button"
                        id="filter-btn-valid"
                        onclick="window.toggleMapLayer('surveyor-map-container','valid'); this.classList.toggle('opacity-40'); this.classList.toggle('line-through')"
                        class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-semibold rounded-full border transition-all
                               bg-green-50 text-green-700 border-green-200 hover:bg-green-100
                               dark:bg-green-500/10 dark:text-green-400 dark:border-green-500/30 dark:hover:bg-green-500/20">
                    <span class="inline-block w-2.5 h-2.5 rounded-full bg-green-500"></span>
                    Valid ({{ $validCount }})
                </button>

                <button type="button"
                        id="filter-btn-pending"
                        onclick="window.toggleMapLayer('surveyor-map-container','pending'); this.classList.toggle('opacity-40'); this.classList.toggle('line-through')"
                        class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-semibold rounded-full border transition-all
                               bg-orange-50 text-orange-700 border-orange-200 hover:bg-orange-100
                               dark:bg-orange-500/10 dark:text-orange-400 dark:border-orange-500/30 dark:hover:bg-orange-500/20">
                    <span class="inline-block w-2.5 h-2.5 rounded-full bg-orange-400"></span>
                    Pending ({{ $pendingCount }})
                </button>

                <button type="button"
                        id="filter-btn-revisi"
                        onclick="window.toggleMapLayer('surveyor-map-container','revisi'); this.classList.toggle('opacity-40'); this.classList.toggle('line-through')"
                        class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-semibold rounded-full border transition-all
                               bg-red-50 text-red-700 border-red-200 hover:bg-red-100
                               dark:bg-red-500/10 dark:text-red-400 dark:border-red-500/30 dark:hover:bg-red-500/20">
                    <span class="inline-block w-2.5 h-2.5 rounded-full bg-red-500"></span>
                    Revisi ({{ $revisiCount }})
                </button>
            </div>
        </div>

        <div class="relative w-full rounded-xl overflow-hidden shadow-inner bg-gray-100 dark:bg-gray-800"
             style="height: 460px;"
             wire:ignore
             x-data
             x-init="window.initFilterableMap('surveyor-map-container', {{ Js::from($locations) }}, {{ $mapCenterLat }}, {{ $mapCenterLng }})">
            <div id="surveyor-map-container" style="width:100%;height:100%;"></div>

            @if(count($locations) === 0)
                <div class="absolute inset-0 flex flex-col items-center justify-center gap-2 text-gray-400 pointer-events-none z-10">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-12 h-12 opacity-30" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1 1 15 0Z"/>
                    </svg>
                    <p class="text-sm font-medium">Belum ada data sensus Anda dengan koordinat GPS.</p>
                </div>
            @endif
        </div>
    </x-filament::section>
</x-filament-widgets::widget>
