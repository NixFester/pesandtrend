@extends('layouts.app')
@section('title', 'Daftar — Pesantrends')

@section('content')
    <section class="bg-cream-50 py-16 sm:py-24">
        <div class="container-app">
            <div class="mx-auto max-w-md">
                <div class="text-center">
                    <span class="mx-auto flex h-14 w-14 items-center justify-center rounded-2xl bg-forest-900 text-gold-400">
                        <x-icon name="sparkles" class="h-7 w-7"/>
                    </span>
                    <h1 class="mt-5 text-2xl font-extrabold tracking-tight text-ink">Bergabung dengan Keluarga Muslim Indonesia</h1>
                    <p class="mt-2 text-sm text-ink-soft">Simpan sekolah favorit, dapatkan rekomendasi, dan akses panduan lengkap — gratis</p>
                </div>

                <div class="card-shadow mt-8 rounded-3xl bg-white p-7 sm:p-8">
                    @include('components.flash')

                    <form method="POST" class="space-y-5">
                        @csrf
                        <div>
                            <label for="name" class="text-sm font-extrabold text-ink">Nama Lengkap</label>
                            <input id="name" type="text" name="name" value="{{ old('name') }}" required autofocus placeholder="cth: Bunda Rahma"
                                   class="input-field mt-2 {{ $errors->has('name') ? '!border-red-400 !ring-red-200' : '' }}">
                        </div>
                        <div>
                            <label for="email" class="text-sm font-extrabold text-ink">Email</label>
                            <input id="email" type="email" name="email" value="{{ old('email') }}" required placeholder="bunda.rahma@email.com"
                                   class="input-field mt-2 {{ $errors->has('email') ? '!border-red-400 !ring-red-200' : '' }}">
                        </div>
                        <div>
                            <label for="password" class="text-sm font-extrabold text-ink">Kata Sandi</label>
                            <input id="password" type="password" name="password" required placeholder="Minimal 8 karakter"
                                   class="input-field mt-2 {{ $errors->has('password') ? '!border-red-400 !ring-red-200' : '' }}">
                        </div>
                        <div>
                            <label for="password_confirmation" class="text-sm font-extrabold text-ink">Konfirmasi Kata Sandi</label>
                            <input id="password_confirmation" type="password" name="password_confirmation" required placeholder="Ulangi kata sandi"
                                   class="input-field mt-2 {{ $errors->has('password') ? '!border-red-400 !ring-red-200' : '' }}">
                        </div>
                        <button type="submit" class="btn-primary w-full !py-4">Daftar Sekarang</button>
                    </form>

                    <p class="mt-6 text-center text-sm text-ink-soft">
                        Sudah punya akun?
                        <a href="{{ route('login') }}" class="font-extrabold text-forest-800 transition hover:text-forest-600">Masuk</a>
                    </p>
                </div>
            </div>
        </div>
    </section>
@endsection
