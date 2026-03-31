<x-filament-widgets::widget>
    <a href="{{ $url }}" class="block group transition-all duration-200 hover:scale-[1.02] focus:outline-none focus:ring-2 focus:ring-{{ $color }}-500 rounded-2xl">
        <div class="flex items-center gap-4 p-5 rounded-2xl border border-gray-200 dark:border-white/10 bg-white dark:bg-gray-900 shadow-sm hover:shadow-md hover:border-{{ $color }}-300 dark:hover:border-{{ $color }}-600 transition-all">
            <div class="flex-shrink-0 p-3 rounded-xl bg-{{ $color }}-50 dark:bg-{{ $color }}-500/10 text-{{ $color }}-600 dark:text-{{ $color }}-400 group-hover:bg-{{ $color }}-100 dark:group-hover:bg-{{ $color }}-500/20 transition-colors">
                @svg($icon, 'w-7 h-7')
            </div>
            <div class="flex-1 min-w-0">
                <p class="text-sm font-bold text-gray-900 dark:text-white truncate">{{ $label }}</p>
                @if($description)
                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5 truncate">{{ $description }}</p>
                @endif
            </div>
            <div class="flex-shrink-0 text-gray-300 dark:text-gray-600 group-hover:text-{{ $color }}-400 transition-colors">
                @svg('heroicon-o-chevron-right', 'w-5 h-5')
            </div>
        </div>
    </a>
</x-filament-widgets::widget>
