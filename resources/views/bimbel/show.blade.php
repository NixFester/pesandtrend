@extends('layouts.app')
@section('title', $mentor->name.' — Bimbel Online')

@section('content')
    {{-- Breadcrumb --}}
    <div class="border-b border-forest-100 bg-white py-3">
        <div class="container-app">
            <x-breadcrumb :items="[
                ['label' => 'Bimbel Online', 'url' => route('bimbel.index')],
                ['label' => $mentor->name],
            ]" />
        </div>
    </div>

    {{-- Mentor Header --}}
    <section class="relative overflow-hidden bg-forest-950 py-16 sm:py-20">
        <div class="absolute inset-0 bg-gradient-to-b from-forest-950/70 to-forest-950"></div>

        <div class="container-app relative">
            <div class="grid gap-10 lg:grid-cols-2">
                <div>
                    <div class="flex items-center gap-4">
                        @if($mentor->image_url)
                            <img src="{{ $mentor->image_url }}" alt="{{ $mentor->name }}"
                                 class="h-20 w-20 rounded-2xl object-cover ring-2 ring-gold-500/40">
                        @else
                            <span class="flex h-20 w-20 items-center justify-center rounded-2xl bg-white/10 text-gold-400 ring-2 ring-gold-500/40">
                                <x-app-icon name="user" class="h-10 w-10"/>
                            </span>
                        @endif
                        <div>
                            <span class="inline-flex items-center gap-1.5 rounded-full bg-gold-500/20 px-3 py-1 text-xs font-bold text-gold-400">
                                <x-app-icon name="graduation-cap" class="h-3.5 w-3.5"/>
                                Mentor Bimbel
                            </span>
                            <h1 class="mt-2 text-2xl font-extrabold tracking-tight text-white sm:text-3xl lg:text-4xl">
                                {{ $mentor->name }}
                            </h1>
                        </div>
                    </div>

                    @if($mentor->tagline)
                        <p class="mt-4 text-sm text-white/70 sm:text-base">{{ $mentor->tagline }}</p>
                    @endif

                    {{-- Expertise --}}
                    @if($mentor->expertise && count($mentor->expertise) > 0)
                        <div class="mt-5 flex flex-wrap gap-2">
                            @foreach($mentor->expertise as $skill)
                                <span class="rounded-full bg-white/10 px-3 py-1 text-xs font-bold text-gold-400">{{ $skill }}</span>
                            @endforeach
                        </div>
                    @endif

                    {{-- Price --}}
                    <div class="mt-8 rounded-2xl border border-white/10 bg-white/5 p-5">
                        <p class="text-xs text-white/60">Harga Layanan Bimbel</p>
                        <p class="mt-1 text-2xl font-extrabold text-gold-400 sm:text-3xl">{{ $mentor->formatted_price }}</p>
                        <p class="mt-1 text-xs text-white/60">Harga tetap, sudah termasuk koordinasi jadwal via WhatsApp.</p>
                    </div>

                    {{-- WhatsApp CTA --}}
                    <a href="{{ $whatsappLink }}" target="_blank" rel="noopener"
                       class="btn-gold mt-6 inline-flex items-center gap-2">
                        <x-app-icon name="brand-whatsapp" class="h-5 w-5" :fill="'currentColor'" />
                        Tanya via WhatsApp
                    </a>
                </div>

                {{-- Booking Form --}}
                <div class="card-shadow-lg rounded-2xl bg-white p-6 sm:p-8">
                    <h2 class="text-lg font-extrabold text-ink">Pesan Bimbel Sekarang</h2>
                    <p class="mt-1 text-sm text-ink-soft">Isi data diri Anda untuk memesan sesi bimbel dengan {{ $mentor->name }}.</p>

                    <form action="{{ route('bimbel.book', $mentor->slug) }}" method="POST" class="mt-6 space-y-5">
                        @csrf

                        {{-- Fixed Price Display --}}
                        <div class="rounded-xl bg-forest-50 p-4">
                            <div class="flex items-center justify-between text-sm">
                                <span class="font-semibold text-ink-soft">Harga Layanan</span>
                                <span class="text-lg font-extrabold text-forest-900">{{ $mentor->formatted_price }}</span>
                            </div>
                        </div>

                        {{-- Client Name --}}
                        <div>
                            <x-form.input label="Nama Lengkap" name="client_name" :value="old('client_name')"
                                          placeholder="Nama Anda" required />
                        </div>

                        {{-- Client Email --}}
                        <div>
                            <x-form.input label="Email" name="client_email" type="email" :value="old('client_email')"
                                          placeholder="email@contoh.com" required />
                        </div>

                        {{-- Client WhatsApp --}}
                        <div>
                            <x-form.input label="No. WhatsApp" name="client_whatsapp" type="tel"
                                          :value="old('client_whatsapp')" placeholder="081234567890" required
                                          hint="Digunakan untuk menghubungi mentor setelah pembayaran." />
                        </div>

                        @if($errors->any())
                            <div class="rounded-xl bg-red-50 p-4 text-sm text-red-600">
                                <ul class="list-inside list-disc">
                                    @foreach($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <button type="submit" class="btn-primary w-full !py-4">
                            <x-app-icon name="credit-card" class="h-5 w-5"/>
                            Pesan & Bayar via Xendit
                        </button>

                        <p class="text-center text-xs text-ink-soft">
                            Pembayaran aman via Xendit. Anda akan diarahkan ke halaman pembayaran.
                        </p>
                    </form>
                </div>
            </div>
        </div>
    </section>

    {{-- Mentor Details --}}
    <section class="bg-white py-16 sm:py-20">
        <div class="container-app">
            <div class="grid gap-10 lg:grid-cols-3">
                <div class="lg:col-span-2">
                    <h2 class="text-xl font-extrabold text-ink">Tentang Mentor</h2>
                    <div class="mt-4 prose prose-forest max-w-none">
                        {!! nl2br(e($mentor->description)) !!}
                    </div>

                    {{-- Gallery --}}
                    @if($mentor->images->count() > 0)
                        <div class="mt-10">
                            <h3 class="text-lg font-extrabold text-ink">Galeri</h3>
                            <p class="mt-1 text-sm text-ink-soft">Sertifikat, portofolio, dan contoh bimbel dari mentor ini.</p>
                            <div class="mt-4 grid grid-cols-2 gap-4 sm:grid-cols-3">
                                @foreach($mentor->images as $image)
                                    <button type="button"
                                            class="mentor-gallery-item group relative block aspect-square overflow-hidden rounded-xl border border-forest-100"
                                            data-full-url="{{ $image->image_url }}"
                                            data-caption="{{ $image->caption ?? \App\Models\MentorImage::types()[$image->type] ?? 'Galeri' }}">
                                        <img src="{{ $image->image_url }}" alt="{{ $image->caption ?? $mentor->name }}"
                                             class="h-full w-full object-cover transition duration-300 group-hover:scale-105">
                                        <span class="absolute left-2 top-2 rounded-full bg-white/90 px-2 py-0.5 text-[10px] font-bold text-ink">
                                            {{ \App\Models\MentorImage::types()[$image->type] ?? 'Lainnya' }}
                                        </span>
                                    </button>
                                @endforeach
                            </div>
                        </div>
                    @endif
                </div>

                <div>
                    {{-- Mentor Info --}}
                    <div class="card-shadow rounded-2xl bg-cream-50 p-6">
                        <h3 class="font-extrabold text-ink">Info Mentor</h3>
                        <dl class="mt-4 space-y-3 text-sm">
                            <div class="flex justify-between">
                                <dt class="text-ink-soft">Nama</dt>
                                <dd class="font-semibold text-ink">{{ $mentor->name }}</dd>
                            </div>
                            <div class="flex justify-between">
                                <dt class="text-ink-soft">Harga</dt>
                                <dd class="font-extrabold text-forest-800">{{ $mentor->formatted_price }}</dd>
                            </div>
                            <div class="flex justify-between">
                                <dt class="text-ink-soft">Status</dt>
                                <dd class="font-semibold text-green-700">Tersedia</dd>
                            </div>
                        </dl>
                    </div>

                    {{-- Contact --}}
                    <div class="mt-6 card-shadow rounded-2xl bg-cream-50 p-6">
                        <h3 class="font-extrabold text-ink">Hubungi Mentor</h3>
                        <p class="mt-2 text-sm text-ink-soft">Punya pertanyaan sebelum memesan? Chat langsung via WhatsApp.</p>
                        <a href="{{ $whatsappLink }}" target="_blank" rel="noopener"
                           class="btn-primary mt-4 inline-flex w-full items-center justify-center gap-2">
                            <x-app-icon name="brand-whatsapp" class="h-5 w-5" :fill="'currentColor'" />
                            Chat WhatsApp
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- Lightbox Modal --}}
    <div id="mentor-lightbox" class="fixed inset-0 z-[100] hidden items-center justify-center bg-black/80 p-4" role="dialog" aria-modal="true">
        <button type="button" id="mentor-lightbox-close" class="absolute right-4 top-4 flex h-10 w-10 items-center justify-center rounded-full bg-white/10 text-white transition hover:bg-white/20" aria-label="Tutup">
            <x-app-icon name="x" class="h-5 w-5"/>
        </button>
        <figure class="max-h-full max-w-3xl">
            <img id="mentor-lightbox-img" src="" alt="" class="mx-auto max-h-[75vh] w-auto rounded-xl">
            <figcaption id="mentor-lightbox-caption" class="mt-3 text-center text-sm text-white/80"></figcaption>
        </figure>
    </div>
@endsection

@push('scripts')
<script>
    (function () {
        function initLightbox() {
            const lightbox = document.getElementById('mentor-lightbox');
            const lightboxImg = document.getElementById('mentor-lightbox-img');
            const lightboxCaption = document.getElementById('mentor-lightbox-caption');
            const closeBtn = document.getElementById('mentor-lightbox-close');
            const items = document.querySelectorAll('.mentor-gallery-item');

            if (!lightbox || !items.length) return;

            function openLightbox(url, caption) {
                lightboxImg.src = url;
                lightboxImg.alt = caption || '';
                lightboxCaption.textContent = caption || '';
                lightbox.classList.remove('hidden');
                lightbox.classList.add('flex');
                document.body.style.overflow = 'hidden';
            }

            function closeLightbox() {
                lightbox.classList.add('hidden');
                lightbox.classList.remove('flex');
                document.body.style.overflow = '';
            }

            items.forEach(function (item) {
                item.addEventListener('click', function () {
                    openLightbox(this.dataset.fullUrl, this.dataset.caption);
                });
            });

            closeBtn.addEventListener('click', closeLightbox);
            lightbox.addEventListener('click', function (e) {
                if (e.target === lightbox) closeLightbox();
            });
            document.addEventListener('keydown', function (e) {
                if (e.key === 'Escape' && !lightbox.classList.contains('hidden')) closeLightbox();
            });
        }

        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', initLightbox);
        } else {
            initLightbox();
        }
    })();
</script>
@endpush
