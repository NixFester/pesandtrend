@extends('layouts.app')
@section('title', 'Edit Profil — Pesantrends')

@section('content')
    <section class="bg-forest-950 py-10">
        <div class="container-app">
            <a href="{{ route('dashboard') }}" class="inline-flex items-center gap-1.5 text-sm font-semibold text-white/60 transition hover:text-white mb-4">
                <x-app-icon name="arrow-left" class="h-4 w-4"/> Kembali ke Dashboard
            </a>
            <h1 class="text-2xl font-extrabold tracking-tight text-white">Edit Profil</h1>
            <p class="mt-1 text-sm text-white/60">Kelola informasi akun dan keamanan Anda.</p>
        </div>
    </section>

    <section class="bg-cream-50 py-10 sm:py-14">
        <div class="container-app max-w-3xl space-y-8">
            @include('components.flash')

            {{-- Informasi Pribadi --}}
            <form action="{{ route('profile.update') }}" method="POST" class="card-shadow rounded-2xl bg-white p-6 sm:p-8">
                @csrf
                @method('PUT')

                <h2 class="flex items-center gap-2.5 text-lg font-extrabold text-ink">
                    <x-app-icon name="user" class="h-5 w-5 text-gold-600"/>
                    Informasi Pribadi
                </h2>

                <div class="mt-6 grid gap-5 sm:grid-cols-2">
                    <div class="sm:col-span-2">
                        <label for="name" class="block text-sm font-bold text-ink mb-1.5">Nama Lengkap</label>
                        <input type="text" id="name" name="name" value="{{ old('name', $user->name) }}" class="input-field @error('name') input-error @enderror" required>
                        @error('name') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label for="email" class="block text-sm font-bold text-ink mb-1.5">Email</label>
                        <input type="email" id="email" name="email" value="{{ old('email', $user->email) }}" class="input-field @error('email') input-error @enderror" required>
                        @error('email') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label for="phone" class="block text-sm font-bold text-ink mb-1.5">Nomor HP</label>
                        <input type="tel" id="phone" name="phone" value="{{ old('phone', $user->phone) }}" class="input-field @error('phone') input-error @enderror" placeholder="08xxxxxxxxxx">
                        @error('phone') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label for="whatsapp" class="block text-sm font-bold text-ink mb-1.5">WhatsApp</label>
                        <input type="tel" id="whatsapp" name="whatsapp" value="{{ old('whatsapp', $user->whatsapp) }}" class="input-field @error('whatsapp') input-error @enderror" placeholder="08xxxxxxxxxx">
                        @error('whatsapp') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label for="address" class="block text-sm font-bold text-ink mb-1.5">Alamat</label>
                        <input type="text" id="address" name="address" value="{{ old('address', $user->address) }}" class="input-field @error('address') input-error @enderror" placeholder="Kota, Provinsi">
                        @error('address') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                    </div>
                </div>

                <div class="mt-6 flex justify-end">
                    <button type="submit" class="btn-primary">
                        <x-app-icon name="check" class="h-4 w-4"/>
                        Simpan Perubahan
                    </button>
                </div>
            </form>

            {{-- Ubah Kata Sandi --}}
            <form action="{{ route('profile.password') }}" method="POST" class="card-shadow rounded-2xl bg-white p-6 sm:p-8">
                @csrf
                @method('PUT')

                <h2 class="flex items-center gap-2.5 text-lg font-extrabold text-ink">
                    <x-app-icon name="lock" class="h-5 w-5 text-gold-600"/>
                    Ubah Kata Sandi
                </h2>

                <div class="mt-6 grid gap-5 sm:grid-cols-2">
                    <div class="sm:col-span-2">
                        <label for="current_password" class="block text-sm font-bold text-ink mb-1.5">Kata Sandi Lama</label>
                        <input type="password" id="current_password" name="current_password" class="input-field @error('current_password') input-error @enderror" required>
                        @error('current_password') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label for="password" class="block text-sm font-bold text-ink mb-1.5">Kata Sandi Baru</label>
                        <input type="password" id="password" name="password" class="input-field @error('password') input-error @enderror" required>
                        @error('password') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label for="password_confirmation" class="block text-sm font-bold text-ink mb-1.5">Konfirmasi Kata Sandi Baru</label>
                        <input type="password" id="password_confirmation" name="password_confirmation" class="input-field" required>
                    </div>
                </div>

                <div class="mt-6 flex justify-end">
                    <button type="submit" class="btn-primary">
                        <x-app-icon name="lock" class="h-4 w-4"/>
                        Ubah Kata Sandi
                    </button>
                </div>
            </form>

            {{-- Info Akun --}}
            <div class="card-shadow rounded-2xl bg-white p-6 sm:p-8">
                <h2 class="flex items-center gap-2.5 text-lg font-extrabold text-ink">
                    <x-app-icon name="info" class="h-5 w-5 text-gold-600"/>
                    Informasi Akun
                </h2>
                <div class="mt-4 grid gap-3 text-sm sm:grid-cols-2">
                    <div>
                        <span class="text-ink-soft">Bergabung sejak</span>
                        <p class="font-bold text-ink">{{ $user->created_at->translatedFormat('d F Y') }}</p>
                    </div>
                    <div>
                        <span class="text-ink-soft">Peran</span>
                        <p class="font-bold text-ink capitalize">{{ $user->role ?? 'Orang Tua' }}</p>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
