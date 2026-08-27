@php($flashType = $flashType ?? 'success')
@if (session('success') || session('newsletter_success') || session('save_success') || isset($errors) && $errors->any())
    @if (session('success'))
        <div x-data class="mb-6 flex items-start gap-3 rounded-2xl border border-forest-200 bg-forest-50 p-4" role="status">
            <x-icon name="check-circle" class="mt-0.5 h-5 w-5 shrink-0 text-forest-700"/>
            <p class="text-sm font-semibold text-forest-900">{{ session('success') }}</p>
        </div>
    @endif
    @if (session('newsletter_success'))
        <div class="mb-6 flex items-start gap-3 rounded-2xl border border-gold-200 bg-gold-50 p-4" role="status">
            <x-icon name="mail" class="mt-0.5 h-5 w-5 shrink-0 text-gold-600"/>
            <p class="text-sm font-semibold text-gold-800">{{ session('newsletter_success') }}</p>
        </div>
    @endif
    @if (session('save_success'))
        <div class="mb-6 flex items-start gap-3 rounded-2xl border border-forest-200 bg-forest-50 p-4" role="status">
            <x-icon name="check-circle" class="mt-0.5 h-5 w-5 shrink-0 text-forest-700"/>
            <p class="text-sm font-semibold text-forest-900">{{ session('save_success') }}</p>
        </div>
    @endif
    @if (isset($errors) && $errors->any())
        <div class="mb-6 rounded-2xl border border-red-200 bg-red-50 p-4" role="alert">
            <ul class="list-inside list-disc text-sm font-semibold text-red-700">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
@endif
