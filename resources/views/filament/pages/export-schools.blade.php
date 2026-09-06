<x-filament-panels::page>
    <x-filament::section>
        <x-slot name="heading">
            Export Data Sekolah
        </x-slot>

        <x-slot name="description">
            Unduh data sekolah yang terdaftar dalam format CSV.
        </x-slot>

        <div class="space-y-6">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 rounded-xl border border-gray-200 dark:border-white/10 bg-gray-50/50 dark:bg-white/5 p-6">
                <div class="flex items-center gap-4">
                    <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-xl bg-success-50 dark:bg-success-500/10 text-success-600 dark:text-success-400">
                        <x-filament::icon icon="heroicon-o-document-arrow-down" class="h-6 w-6 text-success-600 dark:text-success-400" style="width: 24px; height: 24px; min-width: 24px; min-height: 24px;" />
                    </div>
                    <div>
                        <h3 class="text-base font-semibold text-gray-950 dark:text-white">Export Semua Sekolah</h3>
                        <p class="text-sm text-gray-500 dark:text-gray-400">Download semua data sekolah dalam file CSV yang dapat dibuka di Excel atau Google Sheets.</p>
                    </div>
                </div>

                <div class="shrink-0">
                    <x-filament::button
                        tag="a"
                        href="{{ route('filament.admin.resources.schools.export') }}"
                        icon="heroicon-o-arrow-down-tray"
                        color="success"
                        size="lg"
                    >
                        Download CSV
                    </x-filament::button>
                </div>
            </div>

            <div class="rounded-xl border border-gray-200 dark:border-white/10 bg-gray-50/50 dark:bg-white/5 p-4 space-y-3">
                <h4 class="text-xs font-semibold uppercase tracking-wider text-gray-500 dark:text-gray-400">Kolom yang Di-export</h4>
                <div class="flex flex-wrap gap-2">
                    @foreach(['Nama', 'Slug', 'Tipe', 'Kota', 'Provinsi', 'Alamat', 'SPP', 'Asrama', 'Uang Pangkal', 'Akreditasi', 'Jumlah Siswa', 'Tahun Berdiri', 'Berasrama', 'Publikasi', 'Verifikasi', 'Unggulan'] as $col)
                        <x-filament::badge color="gray">
                            {{ $col }}
                        </x-filament::badge>
                    @endforeach
                </div>
            </div>
        </div>
    </x-filament::section>
</x-filament-panels::page>
