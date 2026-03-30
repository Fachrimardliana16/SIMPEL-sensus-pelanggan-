<x-filament-widgets::widget>
    <x-filament::section>
        <div class="flex items-center justify-between gap-x-3">
            <div class="flex-1">
                <h3 class="text-base font-semibold leading-6 text-gray-950 dark:text-white">
                    Akses Cepat
                </h3>
                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                    Gunakan tombol di bawah untuk akses langsung ke fitur utama.
                </p>
            </div>
        </div>

        <div class="mt-6 grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-3">
            @foreach($actions as $action)
                <a href="{{ $action['url'] }}" 
                   class="flex flex-col items-center justify-center p-4 rounded-xl border border-gray-200 dark:border-white/10 hover:bg-gray-50 dark:hover:bg-white/5 transition-all group">
                    <div class="p-3 rounded-lg bg-{{ $action['color'] }}-100 dark:bg-{{ $action['color'] }}-500/20 text-{{ $action['color'] }}-600 dark:text-{{ $action['color'] }}-400 mb-3 group-hover:scale-110 transition-transform">
                        @svg($action['icon'], 'w-8 h-8')
                    </div>
                    <span class="text-sm font-bold text-gray-900 dark:text-white">{{ $action['label'] }}</span>
                </a>
            @endforeach
        </div>
    </x-filament::section>
</x-filament-widgets::widget>
