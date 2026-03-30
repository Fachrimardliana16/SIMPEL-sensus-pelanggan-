<x-filament-widgets::widget>
    <x-filament::section>
        <div class="flex flex-col sm:flex-row gap-4">
            <a href="{{ route('filament.surveyor.resources.sensuses.create') }}"
               class="flex-1 flex items-center justify-center gap-3 px-6 py-5 rounded-xl bg-teal-600 text-white hover:bg-teal-700 transition shadow-lg">
                <x-heroicon-o-plus-circle class="w-8 h-8" />
                <div>
                    <div class="text-lg font-bold">Input Sensus Baru</div>
                    <div class="text-sm opacity-80">Mulai survei pelanggan</div>
                </div>
            </a>
            <a href="{{ route('filament.surveyor.resources.sensuses.index') }}"
               class="flex-1 flex items-center justify-center gap-3 px-6 py-5 rounded-xl bg-gray-600 text-white hover:bg-gray-700 transition shadow-lg">
                <x-heroicon-o-clipboard-document-list class="w-8 h-8" />
                <div>
                    <div class="text-lg font-bold">Lihat Data Saya</div>
                    <div class="text-sm opacity-80">Riwayat sensus Anda</div>
                </div>
            </a>
        </div>
    </x-filament::section>
</x-filament-widgets::widget>
