<?php

namespace App\Http\Controllers;

use App\Models\Subscriber;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class NewsletterController extends Controller
{
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'email' => 'required|email|max:255',
        ], [
            'email.required' => 'Alamat email wajib diisi.',
            'email.email' => 'Format email tidak valid.',
        ]);

        $exists = Subscriber::where('email', $validated['email'])->exists();

        if (! $exists) {
            Subscriber::create($validated);
        }

        return back()->with('newsletter_success', $exists
            ? 'Email Anda sudah terdaftar di newsletter kami.'
            : 'Terima kasih! Artikel pilihan akan dikirim setiap minggu ke email Anda.');
    }
}
