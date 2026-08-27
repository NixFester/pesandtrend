<footer class="bg-forest-950 text-white">
    <div class="container-app py-14">
        <div class="grid gap-10 md:grid-cols-2 lg:grid-cols-4">
            <div class="lg:col-span-2">
                <a href="{{ route('home') }}" class="flex items-center gap-2.5">
                    <span class="flex h-9 w-9 items-center justify-center rounded-xl bg-gold-500 text-forest-950">
                        <x-icon name="book-open" class="h-5 w-5" :stroke="2.2"/>
                    </span>
                    <span class="text-lg font-extrabold tracking-tight text-white">Pesant<span class="text-gold-400">rends</span></span>
                </a>
                <p class="mt-4 max-w-sm text-sm leading-relaxed text-white/70">
                    Platform terpercaya untuk menemukan, membandingkan, dan memilih sekolah Islam terbaik di Indonesia.
                </p>
                <div class="mt-5 flex flex-wrap gap-2">
                    <span class="inline-flex items-center gap-1.5 rounded-full bg-white/10 px-3 py-1.5 text-xs font-semibold text-white/80">
                        <x-icon name="badge-check" class="h-3.5 w-3.5 text-gold-400"/>
                        1.240+ Sekolah Terverifikasi
                    </span>
                    <span class="inline-flex items-center gap-1.5 rounded-full bg-white/10 px-3 py-1.5 text-xs font-semibold text-white/80">
                        <x-icon name="map-pin" class="h-3.5 w-3.5 text-gold-400"/>
                        34 Provinsi
                    </span>
                </div>
            </div>

            <div>
                <h3 class="text-sm font-extrabold uppercase tracking-wider text-gold-400">Jelajahi</h3>
                <ul class="mt-4 space-y-2.5 text-sm">
                    <li><a href="{{ route('schools.index') }}" class="text-white/70 transition hover:text-white">Cari Sekolah</a></li>
                    <li><a href="{{ route('compare.index') }}" class="text-white/70 transition hover:text-white">Bandingkan Sekolah</a></li>
                    <li><a href="{{ route('calculator.index') }}" class="text-white/70 transition hover:text-white">Kalkulator Biaya</a></li>
                    <li><a href="{{ route('articles.index') }}" class="text-white/70 transition hover:text-white">Artikel &amp; Panduan</a></li>
                </ul>
            </div>

            <div>
                <h3 class="text-sm font-extrabold uppercase tracking-wider text-gold-400">Informasi</h3>
                <ul class="mt-4 space-y-2.5 text-sm">
                    <li><a href="#" class="text-white/70 transition hover:text-white">Tentang Kami</a></li>
                    <li><a href="#" class="text-white/70 transition hover:text-white">Hubungi Kami</a></li>
                    <li><a href="#" class="text-white/70 transition hover:text-white">Kebijakan Privasi</a></li>
                    <li><a href="#" class="text-white/70 transition hover:text-white">Syarat &amp; Ketentuan</a></li>
                </ul>
            </div>
        </div>

        <div class="mt-12 flex flex-col items-center justify-between gap-4 border-t border-white/10 pt-6 sm:flex-row">
            <p class="text-xs text-white/50">&copy; {{ date('Y') }} Pesantrends.id — Semua hak dilindungi.</p>
            <p class="flex items-center gap-1.5 text-xs text-white/50">
                Dibuat dengan <span class="text-gold-400" aria-hidden="true">♥</span> untuk pendidikan Islam Indonesia
            </p>
        </div>
    </div>
</footer>
