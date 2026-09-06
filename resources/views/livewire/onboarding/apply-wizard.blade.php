<div class="container-app py-8 max-w-2xl mx-auto">
    {{-- Flash messages --}}
    @if(session('draft_saved'))
    <div class="mb-4 p-4 rounded-xl bg-forest-50 border border-forest-200 text-forest-800 text-sm flex items-center gap-2">
        <x-app-icon name="check-circle" class="h-5 w-5 text-forest-600"/>
        {{ session('draft_saved') }}
    </div>
    @endif

    {{-- Dev mode banner --}}
    @if(config('app.debug'))
    <div class="flex items-center justify-between mb-4 bg-gold-50 border border-gold-200 p-3 rounded-xl">
        <span class="text-xs font-semibold text-gold-800">⚡ Mode Pengujian: Form terisi data contoh.</span>
        <button wire:click="fillTestData" type="button" class="text-xs font-bold text-forest-900 bg-white hover:bg-forest-50 px-3 py-1.5 rounded-lg border border-forest-200 transition">Isi Ulang Data Contoh</button>
    </div>
    @endif

    {{-- Progress bar --}}
    <div class="mb-4 h-1.5 w-full rounded-full bg-forest-100 overflow-hidden" role="progressbar" aria-valuenow="{{ $step }}" aria-valuemin="1" aria-valuemax="5" aria-label="Langkah {{ $step }} dari 5">
        <div class="h-full rounded-full bg-forest-700 transition-all duration-300" style="width: {{ ($step / 5) * 100 }}%"></div>
    </div>

    {{-- Stepper --}}
    <div class="flex items-center justify-between mb-8">
        @foreach(['Sekolah', 'Siswa', 'Orang Tua', 'Dokumen', 'Review'] as $i => $label)
        <div class="flex items-center gap-2 {{ $i > 0 ? 'flex-1' : '' }}">
            @if($i > 0)
            <div class="h-0.5 flex-1 {{ $step > $i ? 'bg-forest-500' : 'bg-forest-100' }}"></div>
            @endif
            <div class="flex flex-col items-center gap-1">
                <div class="flex h-10 w-10 items-center justify-center rounded-full text-sm font-bold transition {{ $step > ($i + 1) ? 'bg-forest-500 text-white' : ($step === ($i + 1) ? 'bg-forest-900 text-white' : 'bg-forest-100 text-ink-soft') }}">
                    {{ $i + 1 }}
                </div>
                {{-- Short label on mobile, full label on sm+ --}}
                <span class="hidden text-[10px] font-semibold sm:block sm:text-xs sm:font-bold sm:text-ink-soft">{{ $label }}</span>
                <span class="text-[10px] font-semibold text-ink-soft sm:hidden">{{ Str::substr($label, 0, 1) }}</span>
            </div>
        </div>
        @endforeach
    </div>

    <div class="rounded-2xl border border-forest-100 p-6 card-shadow">
        {{-- Step 1: Sekolah --}}
        @if($step === 1)
        <h2 class="text-lg font-extrabold text-ink mb-4">Pilih Sekolah</h2>
        <div x-data>
            <select
                wire:model="schoolId"
                x-ref="schoolId"
                x-init="$nextTick(() => @this.autofocusField === 'schoolId' && $refs.schoolId?.focus())"
                class="input-field"
                autocomplete="off"
            >
                <option value="">— Pilih sekolah —</option>
                @foreach($schools as $s)
                <option value="{{ $s->id }}">{{ $s->name }} ({{ $s->city }})</option>
                @endforeach
            </select>
        </div>
        @error('schoolId') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror

        @if($selectedSchool)
        <div class="mt-4 p-4 rounded-xl bg-forest-50 text-sm">
            <strong class="text-forest-800">{{ $selectedSchool->name }}</strong>
            <p class="text-ink-soft mt-1 flex items-center gap-1">
                <x-app-icon name="map-pin" class="h-4 w-4"/>
                {{ $selectedSchool->city }}
            </p>
            @if($selectedSchool->spp_monthly)
            <p class="text-ink-soft mt-1 flex items-center gap-1">
                <x-app-icon name="credit-card" class="h-4 w-4"/>
                SPP: Rp {{ number_format($selectedSchool->spp_monthly, 0, ',', '.') }}/bulan
            </p>
            @endif
        </div>
        @endif
        @endif

        {{-- Step 2: Biodata Siswa --}}
        @if($step === 2)
        <h2 class="text-lg font-extrabold text-ink mb-4">Biodata Siswa</h2>
        <div class="space-y-4" x-data>
            <div>
                <label class="text-sm font-semibold text-ink">
                    Nama Lengkap <span class="text-red-500">*</span>
                </label>
                <input
                    wire:model="studentName"
                    x-ref="studentName"
                    x-init="$nextTick(() => @this.autofocusField === 'studentName' && $refs.studentName?.focus())"
                    type="text"
                    class="input-field mt-1"
                    placeholder="Nama lengkap sesuai akta kelahiran"
                    autocomplete="name"
                >
                @error('studentName') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
            </div>
            <div>
                <label class="text-sm font-semibold text-ink">NIK <span class="text-ink-soft font-normal">(opsional)</span></label>
                <input wire:model="studentNik" type="text" class="input-field mt-1" placeholder="16 digit NIK" maxlength="16" autocomplete="off">
                <p class="text-xs text-ink-soft mt-1">Nomor Induk Kependudukan anak</p>
                @error('studentNik') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
            </div>
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="text-sm font-semibold text-ink">Jenis Kelamin</label>
                    <select wire:model="studentGender" class="input-field mt-1">
                        <option value="">Pilih</option>
                        @foreach($genderOptions as $gender)
                        <option value="{{ $gender }}">{{ $gender }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="text-sm font-semibold text-ink">Tanggal Lahir</label>
                    <input wire:model="studentBirthDate" type="date" class="input-field mt-1" autocomplete="bday">
                </div>
            </div>
            <div>
                <label class="text-sm font-semibold text-ink">Tempat Lahir</label>
                <input wire:model="studentBirthPlace" type="text" class="input-field mt-1" placeholder="cth: Bandung" autocomplete="off">
            </div>
            <div>
                <label class="text-sm font-semibold text-ink">Alamat <span class="text-ink-soft font-normal">(opsional)</span></label>
                <textarea wire:model="studentAddress" class="input-field mt-1" rows="2" placeholder="Alamat lengkap siswa" autocomplete="street-address"></textarea>
            </div>
            <div>
                <label class="text-sm font-semibold text-ink">Asal Sekolah <span class="text-ink-soft font-normal">(opsional)</span></label>
                <input wire:model="previousSchool" type="text" class="input-field mt-1" placeholder="Nama sekolah sebelumnya" autocomplete="off">
            </div>
            <div>
                <label class="text-sm font-semibold text-ink">Jenjang Target <span class="text-red-500">*</span></label>
                <select wire:model="targetJenjang" class="input-field mt-1">
                    <option value="">— Pilih jenjang —</option>
                    @foreach($jenjangOptions as $jenjang)
                    <option value="{{ $jenjang }}">{{ $jenjang }}</option>
                    @endforeach
                </select>
                @error('targetJenjang') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
            </div>
        </div>
        @endif

        {{-- Step 3: Biodata Orang Tua --}}
        @if($step === 3)
        <h2 class="text-lg font-extrabold text-ink mb-4">Biodata Orang Tua / Wali</h2>
        <div class="space-y-4" x-data>
            <div>
                <label class="text-sm font-semibold text-ink">
                    Nama Lengkap <span class="text-red-500">*</span>
                </label>
                <input
                    wire:model="parentName"
                    x-ref="parentName"
                    x-init="$nextTick(() => @this.autofocusField === 'parentName' && $refs.parentName?.focus())"
                    type="text"
                    class="input-field mt-1"
                    placeholder="cth: Budi Santoso"
                    autocomplete="name"
                >
                @error('parentName') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
            </div>
            <div>
                <label class="text-sm font-semibold text-ink">Email</label>
                <input wire:model="parentEmail" type="email" class="input-field mt-1" placeholder="cth: orangtua@email.com" autocomplete="email">
                @error('parentEmail') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
            </div>
            <div>
                <label class="text-sm font-semibold text-ink">Nomor Telepon</label>
                <input wire:model="parentPhone" type="tel" class="input-field mt-1" placeholder="cth: 081234567890" autocomplete="tel">
                <p class="text-xs text-ink-soft mt-1">Nomor HP yang dapat dihubungi (format: 08xx... atau +62xx...)</p>
                @error('parentPhone') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
            </div>
            <div>
                <label class="text-sm font-semibold text-ink">Nomor WhatsApp</label>
                <input wire:model="parentWhatsapp" type="tel" class="input-field mt-1" placeholder="cth: 081234567890" autocomplete="tel">
                <p class="text-xs text-ink-soft mt-1">Akan digunakan untuk menerima info via WhatsApp</p>
                @error('parentWhatsapp') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
            </div>
        </div>
        @endif

        {{-- Step 4: Dokumen --}}
        @if($step === 4)
        <h2 class="text-lg font-extrabold text-ink mb-2">Upload Dokumen</h2>
        <p class="text-sm text-ink-soft mb-6">Upload dokumen pendukung pendaftaran (PDF, JPG, PNG maks. 5MB per berkas). Semua berkas opsional dan dapat dilengkapi nanti.</p>
        
        <div class="space-y-5">
            <div class="p-4 rounded-xl border border-forest-100 bg-forest-50/30">
                <label class="block text-sm font-semibold text-ink mb-1">Kartu Keluarga (KK)</label>
                <input wire:model="docKk" type="file" accept=".pdf,.jpg,.jpeg,.png" class="block w-full text-xs text-ink-soft file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-forest-900 file:text-white hover:file:bg-forest-800 cursor-pointer">
                @error('docKk') <p class="text-red-600 text-xs mt-1">{{ $message }}</p> @enderror
                @if($docKk)
                <p class="text-xs text-forest-700 font-medium mt-1">✓ {{ $docKk->getClientOriginalName() }} dipilih</p>
                @endif
            </div>

            <div class="p-4 rounded-xl border border-forest-100 bg-forest-50/30">
                <label class="block text-sm font-semibold text-ink mb-1">Akta Kelahiran Siswa</label>
                <input wire:model="docAkta" type="file" accept=".pdf,.jpg,.jpeg,.png" class="block w-full text-xs text-ink-soft file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-forest-900 file:text-white hover:file:bg-forest-800 cursor-pointer">
                @error('docAkta') <p class="text-red-600 text-xs mt-1">{{ $message }}</p> @enderror
                @if($docAkta)
                <p class="text-xs text-forest-700 font-medium mt-1">✓ {{ $docAkta->getClientOriginalName() }} dipilih</p>
                @endif
            </div>

            <div class="p-4 rounded-xl border border-forest-100 bg-forest-50/30">
                <label class="block text-sm font-semibold text-ink mb-1">Rapor Terakhir / Ijazah</label>
                <input wire:model="docRapor" type="file" accept=".pdf,.jpg,.jpeg,.png" class="block w-full text-xs text-ink-soft file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-forest-900 file:text-white hover:file:bg-forest-800 cursor-pointer">
                @error('docRapor') <p class="text-red-600 text-xs mt-1">{{ $message }}</p> @enderror
                @if($docRapor)
                <p class="text-xs text-forest-700 font-medium mt-1">✓ {{ $docRapor->getClientOriginalName() }} dipilih</p>
                @endif
            </div>

            <div class="p-4 rounded-xl border border-forest-100 bg-forest-50/30">
                <label class="block text-sm font-semibold text-ink mb-1">Pasfoto Siswa (3x4 / 4x6)</label>
                <input wire:model="docPhoto" type="file" accept=".jpg,.jpeg,.png" class="block w-full text-xs text-ink-soft file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-forest-900 file:text-white hover:file:bg-forest-800 cursor-pointer">
                @error('docPhoto') <p class="text-red-600 text-xs mt-1">{{ $message }}</p> @enderror
                @if($docPhoto)
                <p class="text-xs text-forest-700 font-medium mt-1">✓ {{ $docPhoto->getClientOriginalName() }} dipilih</p>
                @endif
            </div>

            <div>
                <label class="text-sm font-semibold text-ink">Catatan Tambahan</label>
                <textarea wire:model="notes" class="input-field mt-1" rows="3" placeholder="Catatan khusus untuk panitia pendaftaran..."></textarea>
            </div>
        </div>
        @endif

        {{-- Step 5: Review --}}
        @if($step === 5)
        <h2 class="text-lg font-extrabold text-ink mb-4">Review & Kirim</h2>
        <div class="space-y-3 text-sm">
            @if($selectedSchool)
            <div class="p-3 rounded-xl bg-forest-50">
                <strong>Sekolah:</strong> {{ $selectedSchool->name }}
            </div>
            @endif
            <div class="grid sm:grid-cols-2 gap-3">
                <div><strong>Siswa:</strong> {{ $studentName }}</div>
                <div><strong>Jenis Kelamin:</strong> {{ $studentGender ?: '-' }}</div>
                <div><strong>Orang Tua:</strong> {{ $parentName }}</div>
                <div><strong>Email:</strong> {{ $parentEmail ?: '-' }}</div>
                <div><strong>Telepon:</strong> {{ $parentPhone ?: '-' }}</div>
                <div><strong>Jenjang:</strong> {{ $targetJenjang ?: '-' }}</div>
            </div>

            @if($selectedSchool)
            <div class="mt-4 p-4 rounded-xl border border-gold-200 bg-gold-50">
                <h3 class="font-bold text-gold-800 mb-2">Rincian Pembayaran</h3>
                @php
                    $regFee = config('payments.registration_fee', 250000);
                    $total = $regFee + $selectedSchool->uang_pangkal + $selectedSchool->spp_monthly;
                @endphp
                <div class="space-y-1 text-sm">
                    <div class="flex justify-between"><span>Biaya Pendaftaran</span><span>Rp {{ number_format($regFee, 0, ',', '.') }}</span></div>
                    <div class="flex justify-between"><span>Uang Pangkal</span><span>Rp {{ number_format($selectedSchool->uang_pangkal, 0, ',', '.') }}</span></div>
                    <div class="flex justify-between"><span>SPP Bulan Pertama</span><span>Rp {{ number_format($selectedSchool->spp_monthly, 0, ',', '.') }}</span></div>
                    <div class="flex justify-between font-bold border-t border-gold-200 pt-2 mt-2">
                        <span>Total</span>
                        <span>Rp {{ number_format($total, 0, ',', '.') }}</span>
                    </div>
                </div>
            </div>
            @endif
        </div>
        @endif

        {{-- Navigation buttons --}}
        <div class="flex items-center justify-between mt-6 pt-4 border-t border-forest-100">
            @if($step > 1)
            <button wire:click="previousStep" type="button" class="btn-outline">Kembali</button>
            @else
            <div></div>
            @endif

            @if($step < 5)
            <button wire:click="nextStep" type="button" class="btn-primary">Lanjutkan</button>
            @else
            <div class="flex items-center gap-3">
                <button wire:click="saveDraft" wire:loading.attr="disabled" type="button" class="btn-outline">
                    <span wire:loading.remove class="flex items-center gap-2">
                        <x-app-icon name="save" class="h-4 w-4"/> Simpan Draft
                    </span>
                    <span wire:loading class="flex items-center gap-2">
                        <svg class="animate-spin h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                    </span>
                </button>
                <button wire:click="submit" wire:loading.attr="disabled" type="button" class="btn-gold flex items-center gap-2">
                    <span wire:loading.remove class="flex items-center gap-2">
                        <x-app-icon name="send" class="h-4 w-4"/> Kirim & Bayar
                    </span>
                    <span wire:loading class="flex items-center gap-2">
                        <svg class="animate-spin h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                        Memproses...
                    </span>
                </button>
            </div>
            @endif
        </div>
    </div>
</div>
