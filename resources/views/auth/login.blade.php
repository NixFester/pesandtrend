@extends('layouts.app')
@section('title', 'Masuk — Pesantrends')

@section('content')
    <section class="bg-cream-50 py-16 sm:py-24">
        <div class="container-app">
            <div class="mx-auto max-w-md">
                <div class="text-center">
                    <span class="mx-auto flex h-14 w-14 items-center justify-center rounded-2xl bg-forest-900 text-gold-400">
                        <x-icon name="book-open" class="h-7 w-7" :stroke="2.2"/>
                    </span>
                    <h1 class="mt-5 text-2xl font-extrabold tracking-tight text-ink">Selamat Datang Kembali</h1>
                    <p class="mt-2 text-sm text-ink-soft">Masuk untuk melihat sekolah tersimpan dan rekomendasi pilihan</p>
                </div>

                <div class="card-shadow mt-8 rounded-3xl bg-white p-7 sm:p-8">
                    @include('components.flash')

                    <form method="POST" class="space-y-5">
                        @csrf
                        <div>
                            <label for="email" class="text-sm font-extrabold text-ink">Email</label>
                            <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus placeholder="nama@email.com"
                                   class="input-field mt-2 {{ $errors->has('email') ? '!border-red-400 !ring-red-200' : '' }}">
                        </div>
                        <div>
                            <label for="password" class="text-sm font-extrabold text-ink">Kata Sandi</label>
                            <input id="password" type="password" name="password" required placeholder="••••••••"
                                   class="input-field mt-2 {{ $errors->has('password') ? '!border-red-400 !ring-red-200' : '' }}">
                        </div>
                        <label class="flex items-center gap-2.5 text-sm font-semibold text-ink-soft">
                            <input type="checkbox" name="remember" class="h-4 w-4 rounded border-forest-200 text-forest-900 focus:ring-forest-600">
                            Ingat saya
                        </label>
                        <button type="submit" class="btn-primary w-full !py-4">Masuk</button>
                    </form>

                    <p class="mt-6 text-center text-sm text-ink-soft">
                        Belum punya akun?
                        <a href="{{ route('register') }}" class="font-extrabold text-forest-800 transition hover:text-forest-600">Daftar gratis</a>
                    </p>
                </div>
            </div>
        </div>
    </section>
@endsection
