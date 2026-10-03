@extends('layouts.app')
@section('title', $campaign->title.' — Bantu Pesantren')

@section('content')
    {{-- Breadcrumb --}}
    <div class="border-b border-forest-100 bg-white py-3">
        <div class="container-app">
            <x-breadcrumb :items="[
                ['label' => 'Bantu Pesantren', 'url' => route('donations.index')],
                ['label' => $campaign->school->name],
            ]" />
        </div>
    </div>

    {{-- Campaign Header --}}
    <section class="relative overflow-hidden bg-forest-950 py-16 sm:py-20">
        <img src="{{ $campaign->imageUrl }}" alt="{{ $campaign->title }}"
             class="absolute inset-0 h-full w-full object-cover opacity-30">
        <div class="absolute inset-0 bg-gradient-to-b from-forest-950/70 to-forest-950"></div>

        <div class="container-app relative">
            <div class="grid gap-10 lg:grid-cols-2">
                <div>
                    <span class="inline-flex items-center gap-1.5 rounded-full bg-gold-500/20 px-3 py-1 text-xs font-bold text-gold-400">
                        <x-app-icon name="heart" class="h-3.5 w-3.5"/>
                        {{ $categories[$campaign->category] ?? $campaign->category }}
                    </span>

                    <h1 class="mt-4 text-2xl font-extrabold tracking-tight text-white sm:text-3xl lg:text-4xl">
                        {{ $campaign->title }}
                    </h1>

                    <p class="mt-3 text-sm text-white/70">
                        Untuk <a href="{{ route('schools.show', $campaign->school) }}" class="font-semibold text-gold-400 hover:underline">
                            {{ $campaign->school->name }}
                        </a>, {{ $campaign->school->city }}
                    </p>

                    {{-- Progress Bar --}}
                    <div class="mt-8">
                        <div class="flex items-center justify-between text-sm">
                            <span class="font-semibold text-gold-400">{{ $campaign->formatted_current }}</span>
                            <span class="text-white/60">dari {{ $campaign->formatted_target }}</span>
                        </div>
                        <div class="mt-2 h-4 w-full overflow-hidden rounded-full bg-white/20">
                            <div class="h-full rounded-full bg-gradient-to-r from-gold-500 to-gold-400 transition-all"
                                 style="width: {{ $campaign->progress_percentage }}%"></div>
                        </div>
                        <div class="mt-2 flex items-center justify-between text-xs text-white/60">
                            <span>{{ $campaign->progress_percentage }}% tercapai</span>
                            @if($campaign->days_left !== null)
                                <span>{{ $campaign->days_left }} hari lagi</span>
                            @endif
                        </div>
                    </div>
                </div>

                {{-- Donation Form --}}
                <div class="card-shadow-lg rounded-2xl bg-white p-6 sm:p-8">
                    <h2 class="text-lg font-extrabold text-ink">Donasi Sekarang</h2>

                    <form action="{{ route('donations.donate', $campaign->slug) }}" method="POST" class="mt-6 space-y-5">
                        @csrf

                        {{-- Preset Amounts --}}
                        <div>
                            <label class="block text-sm font-semibold text-ink">Pilih Nominal</label>
                            <div class="mt-2 grid grid-cols-3 gap-2.5">
                                @foreach($presetAmounts as $amount)
                                    @php
                                        $label = $amount >= 1000000
                                            ? 'Rp' . ($amount / 1000000) . 'jt'
                                            : 'Rp' . ($amount / 1000) . 'rb';
                                        $isActive = (int) old('amount', 100000) === (int) $amount;
                                    @endphp
                                    <button type="button"
                                            data-preset-amount="{{ $amount }}"
                                            class="preset-amount-btn rounded-xl border-2 py-3 px-2 text-center text-sm font-bold transition-all duration-200 cursor-pointer focus:outline-none focus-visible:ring-2 focus-visible:ring-forest-900 focus-visible:ring-offset-2 {{ $isActive ? 'border-forest-900 bg-forest-900 text-white shadow-sm ring-2 ring-forest-900/20' : 'border-forest-100 bg-white text-ink-soft hover:border-forest-300 hover:bg-forest-50/60 hover:text-forest-900' }}">
                                        {{ $label }}
                                    </button>
                                @endforeach
                            </div>
                        </div>

                        {{-- Custom Amount --}}
                        <div>
                            <label for="custom-amount" class="block text-sm font-semibold text-ink">Atau masukkan nominal lain</label>
                            <div class="relative mt-2">
                                <span class="pointer-events-none absolute left-4 top-1/2 -translate-y-1/2 text-sm font-bold text-ink-soft select-none">Rp</span>
                                <input type="number" name="amount" id="custom-amount" min="10000" max="100000000"
                                       value="{{ old('amount', 100000) }}"
                                       placeholder="100000"
                                       class="input-field !pl-14">
                            </div>
                            @error('amount')
                                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Donor Name --}}
                        <div>
                            <x-form.input label="Nama Lengkap" name="donor_name" :value="old('donor_name')"
                                          placeholder="Nama Anda" required />
                        </div>

                        {{-- Donor Email --}}
                        <div>
                            <x-form.input label="Email" name="donor_email" type="email" :value="old('donor_email')"
                                          placeholder="email@contoh.com" required />
                        </div>

                        {{-- Donor Phone --}}
                        <div>
                            <x-form.input label="No. WhatsApp (opsional)" name="donor_phone" type="tel"
                                          :value="old('donor_phone')" placeholder="081234567890" />
                        </div>

                        {{-- Donor Message --}}
                        <div>
                            <label class="block text-sm font-semibold text-ink">Pesan (opsional)</label>
                            <textarea name="donor_message" rows="2"
                                      placeholder="Semoga bermanfaat..."
                                      class="input-field mt-2">{{ old('donor_message') }}</textarea>
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
                            <x-app-icon name="heart" class="h-5 w-5"/>
                            Donasi Sekarang
                        </button>

                        <p class="text-center text-xs text-ink-soft">
                            Pembayaran aman via Xendit. Dana akan langsung ke campaign.
                        </p>
                    </form>
                </div>
            </div>
        </div>
    </section>

    {{-- Campaign Details --}}
    <section class="bg-white py-16 sm:py-20">
        <div class="container-app">
            <div class="grid gap-10 lg:grid-cols-3">
                <div class="lg:col-span-2">
                    <h2 class="text-xl font-extrabold text-ink">Tentang Campaign</h2>
                    <div class="mt-4 prose prose-forest max-w-none">
                        {!! nl2br(e($campaign->description)) !!}
                    </div>

                    {{-- Recent Donations --}}
                    @if($recentDonations->count() > 0)
                        <div class="mt-10">
                            <h3 class="text-lg font-extrabold text-ink">Donasi Terbaru</h3>
                            <div class="mt-4 space-y-3">
                                @foreach($recentDonations->take(5) as $donation)
                                    <div class="flex items-center justify-between rounded-xl bg-forest-50/50 p-4">
                                        <div class="flex items-center gap-3">
                                            <span class="flex h-10 w-10 items-center justify-center rounded-full bg-forest-900 text-sm font-extrabold text-gold-400">
                                                {{ Str::substr($donation->donor_name, 0, 1) }}
                                            </span>
                                            <div>
                                                <p class="font-semibold text-ink">{{ $donation->donor_name }}</p>
                                                @if($donation->donor_message)
                                                    <p class="text-xs text-ink-soft italic">"{{ Str::limit($donation->donor_message, 50) }}"</p>
                                                @endif
                                            </div>
                                        </div>
                                        <span class="font-bold text-forest-800">{{ $donation->formatted_amount }}</span>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif
                </div>

                <div>
                    {{-- School Info --}}
                    <div class="card-shadow rounded-2xl bg-cream-50 p-6">
                        <h3 class="font-extrabold text-ink">Tentang Sekolah</h3>
                        <div class="mt-4">
                            <a href="{{ route('schools.show', $campaign->school) }}" class="flex items-center gap-3">
                                <img src="{{ $campaign->school->image_url }}" alt="{{ $campaign->school->name }}"
                                     class="h-12 w-12 rounded-xl object-cover">
                                <div>
                                    <p class="font-semibold text-ink">{{ $campaign->school->name }}</p>
                                    <p class="text-xs text-ink-soft">{{ $campaign->school->city }}, {{ $campaign->school->province }}</p>
                                </div>
                            </a>
                        </div>
                    </div>

                    {{-- Share --}}
                    <div class="mt-6 card-shadow rounded-2xl bg-cream-50 p-6">
                        <h3 class="font-extrabold text-ink">Bagikan Campaign</h3>
                        <div class="mt-4 flex flex-wrap gap-2">
                            <a href="https://wa.me/?text={{ urlencode($campaign->title.' - '.route('donations.show', $campaign->slug)) }}"
                               target="_blank" rel="noopener"
                               class="flex h-10 w-10 items-center justify-center rounded-xl bg-[#25D366] text-white transition hover:bg-[#20bd5a]">
                                <x-app-icon name="brand-whatsapp" class="h-5 w-5" :fill="'currentColor'" />
                            </a>
                            <a href="https://www.instagram.com/sharer/sharer.php?u={{ urlencode(route('donations.show', $campaign->slug)) }}"
                               target="_blank" rel="noopener"
                               class="flex h-10 w-10 items-center justify-center rounded-xl bg-gradient-to-br from-[#833AB4] via-[#FD1D1D] to-[#F77737] text-white transition hover:opacity-90">
                                <x-app-icon name="brand-instagram" class="h-5 w-5" :fill="'currentColor'" />
                            </a>
                            <a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode(route('donations.show', $campaign->slug)) }}"
                               target="_blank" rel="noopener"
                               class="flex h-10 w-10 items-center justify-center rounded-xl bg-blue-600 text-white transition hover:bg-blue-700">
                                <x-app-icon name="brand-facebook" class="h-5 w-5" :fill="'currentColor'" />
                            </a>
                            <a href="https://twitter.com/intent/tweet?text={{ urlencode($campaign->title) }}&url={{ urlencode(route('donations.show', $campaign->slug)) }}"
                               target="_blank" rel="noopener"
                               class="flex h-10 w-10 items-center justify-center rounded-xl bg-sky-500 text-white transition hover:bg-sky-600">
                                <x-app-icon name="brand-twitter" class="h-5 w-5" :fill="'currentColor'" />
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@push('scripts')
<script>
    (function () {
        function initPresetAmounts() {
            const amountInput = document.getElementById('custom-amount');
            const presetButtons = document.querySelectorAll('.preset-amount-btn');

            if (!presetButtons.length || !amountInput) return;

            const activeClasses = ['border-forest-900', 'bg-forest-900', 'text-white', 'shadow-sm', 'ring-2', 'ring-forest-900/20'];
            const inactiveClasses = ['border-forest-100', 'bg-white', 'text-ink-soft', 'hover:border-forest-300', 'hover:bg-forest-50/60', 'hover:text-forest-900'];

            function syncActiveState(value) {
                const num = parseInt(value, 10);
                presetButtons.forEach(btn => {
                    const btnVal = parseInt(btn.dataset.presetAmount, 10);
                    if (btnVal === num) {
                        btn.classList.remove(...inactiveClasses);
                        btn.classList.add(...activeClasses);
                    } else {
                        btn.classList.remove(...activeClasses);
                        btn.classList.add(...inactiveClasses);
                    }
                });
            }

            presetButtons.forEach(btn => {
                btn.addEventListener('click', function () {
                    const selectedVal = this.dataset.presetAmount;
                    amountInput.value = selectedVal;
                    syncActiveState(selectedVal);
                    amountInput.dispatchEvent(new Event('input', { bubbles: true }));
                    amountInput.dispatchEvent(new Event('change', { bubbles: true }));
                });
            });

            amountInput.addEventListener('input', function () {
                syncActiveState(this.value);
            });

            // Initial sync on load
            syncActiveState(amountInput.value);
        }

        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', initPresetAmounts);
        } else {
            initPresetAmounts();
        }
    })();
</script>
@endpush

