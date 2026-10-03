# Implementation Plan: Bimbel Online (Mentor Marketplace)

## Goal

Simple mentor marketplace: clients browse tutors, view profiles (gallery: certificates/portfolio/bimbel examples), pay a fixed price via Xendit, then contact the mentor via WhatsApp with a downloadable PDF payment proof.

No new composer packages. Reuses existing Xendit, Filament v5, and dompdf infrastructure.

## Decisions (confirmed)

| Decision | Choice |
|---|---|
| Pricing | Fixed price per mentor; amount snapshotted onto booking at creation; not client-editable |
| Surfacing | Navbar link only (desktop + mobile), consistent with Bantu Pesantren. No homepage section |
| Booking code | Human-readable unique code (e.g. `BIM-8F3K2A`) on success page, PDF proof, WhatsApp message |
| Expertise | `json` column, Filament `TagsInput`, rendered as tag chips |
| Payment flow | Mirrors donations exactly: form → booking + Xendit invoice → redirect → webhook → success (self-healing) with signed PDF proof + `wa.me` deep link |
| external_id | `mentor_booking_{id}` (per plan.md) |
| Legacy callback route | Not created for bimbel — webhook is the authoritative path |

## Deviations from plan.md (resolved)

1. **Success/failed routes** include the booking id for unambiguous binding: `/bimbel/{slug}/sukses/{booking}` and `/bimbel/{slug}/gagal/{booking}` (plan.md had slug-only, which cannot identify which booking to show).
2. **Mentor images** are managed via a Filament `Repeater` inside the Mentor form (drag-drop reorder + upload on one page, matches admin requirement "edit mentor details & images"). `MentorImageResource` from plan.md's file list is dropped.
3. **Booking statuses**: `pending|paid|failed|expired|cancelled` (plan.md lacked `failed`; failed page needs it). Webhook marks `paid`; `failed/expired/cancelled` are admin-editable manually.
4. **New admin nav group** `'Bimbel'` in `AdminPanelProvider::$navigationGroups`.

## Key codebase patterns to mirror (verified)

- `app/Services/XenditService.php` — raw `Http::withBasicAuth` Invoice API v2; mock mode when secret empty/contains `"dummy"` (invoice_url → dev simulasi route); webhook token check via `hash_equals`; idempotency via `XenditWebhookEvent`; **extension point**: prefix dispatch in `processInvoicePaid()` (`donation_` branch today).
- `app/Http/Controllers/DonationController.php` + `app/Services/DonationService.php` — booking flow, inline `Validator`, `redirect()->away($invoice_url)`, dev `simulatePayment`, success-page self-heal (synthesizes `['status'=>'PAID']` callback if still pending).
- Filament v5 (subdirectory resources): thin `Resource` + `Schemas/XForm::configure($schema)` + `Tables/XTable::configure($table)`; see `app/Filament/Resources/{Campaigns,Donations}/`.
- PDF proof: `ProofController@print` (signed route, 404 unless paid) + `app/Services/PdfRenderer.php` + standalone `resources/views/payments/proof.blade.php`.
- Slug: manual `Str::slug()` in `static::creating` hook (Campaign pattern). Statuses: plain strings + `markAsPaid()` transitions (no PHP enums).
- Views: `@extends('layouts.app')`, `@section('title')`/`@section('content')`, `@push('scripts')` for vanilla JS.
- Tests: PHPUnit 12, `tests/Feature/*Test.php`, `RefreshDatabase`; webhook test pattern in `tests/Feature/XenditWebhookTest.php`; signed-URL pattern in `tests/Feature/ProofPaymentTest.php`.

## Ordered Tasks

### 1. Migrations (3 tables)

Use `php artisan make:migration` — names must be date-prefixed, not literal names from plan.md.

- **`mentors`**: `id`, `name`, `slug` unique, `tagline` nullable string, `description` text, `expertise` json nullable, `price` unsignedBigInteger, `whatsapp_number` string, `is_active` bool default true, `sort_order` unsignedInteger default 0, `timestamps`; index(`is_active`, `sort_order`).
- **`mentor_images`**: `id`, `foreignId('mentor_id')->constrained()->cascadeOnDelete()`, `image_path`, `caption` nullable, `type` enum(`certificate`,`portfolio`,`example`,`other`) default `other`, `sort_order` unsignedInteger default 0, `timestamps`.
- **`mentor_bookings`**: `id`, `code` string unique (nullable initially, set on create), `foreignId('mentor_id')->constrained()->cascadeOnDelete()`, `client_name`, `client_email`, `client_whatsapp`, `amount` unsignedBigInteger, `payment_method` nullable, `payment_channel` nullable, `xendit_id` string nullable unique, `external_id` string nullable, `invoice_url` string nullable, `status` enum(`pending`,`paid`,`failed`,`expired`,`cancelled`) default `pending`, `paid_at` timestamp nullable, `timestamps`; index(`mentor_id`, `status`), index(`client_email`). Plan `invoice_url` at creation time (donations needed a follow-up migration).

### 2. Models + factories

**`app/Models/Mentor.php`**
- fillable: all columns except timestamps/code handling; casts: `expertise` `array`, `price` `integer`, `is_active` `boolean`.
- `static::creating` hook: `Str::slug($name)` when empty.
- Relations: `images(): HasMany` (ordered `sort_order`), `bookings(): HasMany`.
- Scopes: `scopeActive`, `scopeOrdered` (`sort_order` asc, `name` asc).
- Accessors: `formatted_price` (Rp formatting — copy Campaign's formatter), `image_url` (first image or placeholder).
- `whatsapp_link(string $message): string` — normalize: `preg_replace('/\D/', '', $number)`; if starts with `0` → `'62'.substr($n,1)`; if starts with `8` → `'62'.$n`; return `https://wa.me/{n}?text=`.urlencode($message).
- `expertiseLabel` not needed — chips render from array.

**`app/Models/MentorImage.php`**: fillable, `type`/`sort_order`; `mentor()` BelongsTo.

**`app/Models/MentorBooking.php`**
- fillable: all except `code` handling; casts: `amount` integer, `paid_at` datetime.
- `static::creating` hook: `if (empty($code)) $code = 'BIM-'.strtoupper(Str::random(6))` (retry on collision is acceptable at this scale; column is unique).
- Relations: `mentor()` BelongsTo.
- Scopes: `scopePaid`, `scopePending`.
- `markAsPaid(?string $xenditId = null): void` — status `paid`, `paid_at = now()`, set `xendit_id` when provided; no counters (unlike Campaign).
- Accessors: `formatted_amount`, `is_paid`; `external_id()` accessor returning `'mentor_booking_'.$this->id`.

**Factories** (follow `CampaignFactory` style):
- `database/factories/MentorFactory.php` — states `active()`, `inactive()`; name unique, price 100000–2000000, whatsapp `08xxxxxxxxxx`.
- `database/factories/MentorBookingFactory.php` — states `pending()`, `paid()` (sets `paid_at`, `xendit_id`); links mentor; client fields from faker.
- Optional `MentorImageFactory` only if tests need it.

### 3. XenditService additions (`app/Services/XenditService.php`)

- **`createMentorBookingInvoice(MentorBooking $booking): array`** — clone `createDonationInvoice()` structure:
  - Mock mode when secret empty/contains `"dummy"`: fake invoice, `invoice_url` → `route('bimbel.simulate-payment', $booking)`.
  - Payload: `external_id = 'mentor_booking_'.$booking->id`, `amount = $booking->amount`, `description = 'Pembayaran Bimbel - '.$booking->mentor->name`, `payer_email = $booking->client_email`, `customer{given_names: $booking->client_name, email}`, `currency: 'IDR'`, `invoice_duration: 86400`, `success_redirect_url = url('/bimbel/'.$booking->mentor->slug.'/sukses/'.$booking->id)`, `failure_redirect_url = url('/bimbel/'.$booking->mentor->slug.'/gagal/'.$booking->id)`.
  - On API failure: log + throw (same as donations). Return payload array (`id`, `invoice_url`, `status`).
- **`processInvoicePaid()`**: add branch before the ApplicationPayment fallback:
  ```php
  if (str_starts_with($externalId, 'mentor_booking_')) {
      $this->processMentorBookingPaid($payload);
      return;
  }
  ```
- **`processMentorBookingPaid(array $payload)`** (private): strip prefix → `MentorBooking::find()` → skip if already paid → `markAsPaid($payload['id'] ?? null)`.

### 4. BimbelService (`app/Services/BimbelService.php`)

- `createBooking(Mentor $mentor, array $data): MentorBooking` — abort unless `$mentor->is_active`; `MentorBooking::create([... status 'pending', 'amount' => $mentor->price, 'external_id' => 'mentor_booking_'.$id after create])` — note: create first to get id, then update `external_id` (or set via created event); then `XenditService::createMentorBookingInvoice()`; update booking with `xendit_id`, `payment_method` (e.g. `'XENDIT'`), `invoice_url`.
- `handleCallback(array $payload): void` — match `external_id` starting `mentor_booking_`; status `PAID` → `markAsPaid`; `EXPIRED`/`FAILED` → set status accordingly; used by success-page self-heal and available for future callback routes.
- `formatAmount(int $amount): string` — Rp formatting (or reuse existing helper).

### 5. Routes (`routes/web.php`)

New prefix group (place near the `bantu-pesantren` group):

```php
Route::prefix('bimbel')->group(function () {
    Route::get('/', [BimbelController::class, 'index'])->name('bimbel.index');
    Route::get('/{slug}', [BimbelController::class, 'show'])->name('bimbel.show');
    Route::post('/{slug}/pesan', [BimbelController::class, 'book'])->name('bimbel.book');
    Route::get('/pembayaran/{booking}', [BimbelController::class, 'payment'])->name('bimbel.payment');
    Route::get('/pembayaran/{booking}/proses', [BimbelController::class, 'processPayment'])->name('bimbel.process-payment');
    Route::get('/pembayaran/{booking}/simulasi', [BimbelController::class, 'simulatePayment'])->name('bimbel.simulate-payment');
    Route::get('/{slug}/sukses/{booking}', [BimbelController::class, 'success'])->name('bimbel.success');
    Route::get('/{slug}/gagal/{booking}', [BimbelController::class, 'failed'])->name('bimbel.failed');
});
Route::get('/bimbel/bukti/{booking}/cetak', [ProofController::class, 'printMentorBooking'])
    ->middleware('signed')->name('bimbel.proof');
```

- `{booking}` implicit binding → `MentorBooking`. The `{slug}` segment is cosmetic; do not 404 on slug mismatch (id in path is authoritative).
- No auth middleware (public, like donations). No legacy callback route.
- Verify with `php artisan route:list --name=bimbel` after adding.

### 6. BimbelController (`app/Http/Controllers/BimbelController.php`)

Constructor-inject `BimbelService`. Mirror `DonationController` method-for-method:

- `index(Request): View` — `Mentor::with('images')->active()->ordered()->paginate(9)`; pass stats (mentor count, total paid bookings amount via `MentorBooking::paid()`).
- `show(string $slug): View` — `firstOrFail` on slug + `active()` scope; `load('images')`; pass `whatsapp_link` for pre-booking contact (message: "Halo {name}, saya ingin bertanya tentang bimbel...").
- `book(Request, string $slug): RedirectResponse` — inline `Validator`: `client_name required|string|max:100`, `client_email required|email|max:255`, `client_whatsapp required|string|max:30`. Delegate to `BimbelService::createBooking()`; redirect to `route('bimbel.payment', $booking)`.
- `payment(MentorBooking): View` — order summary (mentor, client, amount); primary CTA → `bimbel.process-payment`; `@if(app()->environment('local','development'))` block → `bimbel.simulate-payment` (copy donations `payment.blade.php` pattern exactly).
- `processPayment(MentorBooking): RedirectResponse` — `redirect()->away($booking->invoice_url)` with fallback checkout URL like donations.
- `simulatePayment(MentorBooking): RedirectResponse` — dev-only: `markAsPaid(null)`, `payment_method = 'XENDIT_SIMULATED'`, redirect to success.
- `success(MentorBooking): View` — self-heal: if `status === 'pending' && xendit_id`, call `BimbelService::handleCallback(['external_id' => 'mentor_booking_'.$booking->id, 'status' => 'PAID', 'id' => $booking->xendit_id])`, refresh model; pass signed proof URL (`URL::signedRoute('bimbel.proof', $booking)`) and mentor `whatsapp_link` with pre-filled message:
  `Halo {mentor name}, saya {client_name} sudah melakukan pembayaran bimbel via Pesantrends (Kode: {code}). Bukti: {proof_url}`
- `failed(MentorBooking): View` — static failure causes + retry links (mirror donations `failed.blade.php`).

### 7. PDF proof

- `app/Services/PdfRenderer.php`: add `mentorBookingProof(MentorBooking $booking)` → `Pdf::loadView('bimbel.proof', [...])->setPaper('a4')->output()`.
- `app/Http/Controllers/ProofController.php`: add `printMentorBooking(MentorBooking $booking)` — `abort_unless($booking->isPaid(), 404)`; return PDF download `attachment` filename `bukti-bimbel-{code}.pdf`.
- `resources/views/bimbel/proof.blade.php` — standalone HTML (no layout), copy structure of `payments/proof.blade.php`: platform header, "Bukti Pembayaran Bimbel", info grid (kode booking, nama klien, WhatsApp, mentor, invoice Xendit ID, metode), amount + paid_at, green "TERBAYAR" stamp, footer note.

### 8. Blade views (`resources/views/bimbel/`)

All extend `layouts.app`; components: `container-app`, `btn-primary`, `input-field`, `card-shadow`, `section-label`, `x-app-icon`, `x-breadcrumb`, `x-empty-state`, `x-form.input` — copy usage from `resources/views/donations/*`.

- **`index.blade.php`** — dark hero ("Bimbel Online" + short description), stats dl (jumlah mentor, jumlah booking), grid of `x-mentor-card`, empty state, dark CTA. Pagination.
- **`show.blade.php`** — breadcrumb; dark header (photo, name, tagline, expertise chips, formatted price, WhatsApp button); gallery section (grid of mentor_images grouped/labeled by type — certificate/portfolio/example); description; payment form card: `x-form.input` name/email/WhatsApp + hidden nothing (amount displayed read-only = formatted price), CSRF, POST `bimbel.book` with slug; vanilla JS via `@push('scripts')` for gallery lightbox (click image → modal overlay, ESC/click to close).
- **`payment.blade.php`** — summary card (mentor, kode placeholder, nama/email/amount) + "Bayar via Xendit" CTA → `bimbel.process-payment` + local-only simulasi block.
- **`success.blade.php`** — check icon; booking detail dl (kode, mentor, jumlah, status, tanggal bayar); two CTAs: "Download Bukti Pembayaran" (→ `route('bimbel.proof', $booking)`, direct download) and "Chat WhatsApp Mentor" (`$booking->mentor->whatsapp_link(...)` with pre-filled message, `target="_blank"`); links back to `/bimbel` and mentor detail.
- **`failed.blade.php`** — red icon, possible-causes list, links to retry (`bimbel.show`) and catalog.
- **`resources/views/components/mentor-card.blade.php`** — photo (or placeholder), name, tagline, expertise chips loop, formatted price, "Lihat Detail" link → `route('bimbel.show', $mentor)`. Mirror `campaign-card.blade.php` styling.

### 9. Navbar integration

`resources/views/components/navbar.blade.php` — add "Bimbel" link in both desktop (~line 30) and mobile (~line 64) menus, next to "Bantu Pesantren": `route('bimbel.index')` with active state `request()->routeIs('bimbel.*') ? $navActiveClass : $navLinkClass`. No footer/homepage changes (per decision).

### 10. Filament admin (v5 pattern)

**`app/Filament/Resources/Mentors/MentorResource.php`** — nav `heroicon-o-academic-cap`, group `'Bimbel'`, sort 1; delegates to `Schemas/MentorForm` + `Tables/MentorsTable`; standard List/Create/Edit pages (Edit: DeleteAction header, like CampaignResource).

- **`Schemas/MentorForm.php`** (`Filament\Schemas\Schema`, `Filament\Schemas\Components\Section`):
  - "Informasi Mentor": name TextInput, tagline TextInput, `expertise` TagsInput (json array), whatsapp_number TextInput.
  - "Deskripsi": Textarea rows 4.
  - "Harga & Pengaturan": price numeric with Rp prefix (copy CampaignForm's numeric pattern), `is_active` Toggle default true, `sort_order` Number default 0.
  - "Galeri Gambar": Repeater (label from `MentorImage::` types: Sertifikat/Portofolio/Contoh Bimbel/Lainnya) with `FileUpload` (disk public, directory `mentors/{uuid}/`, imageEditor), caption TextInput, type Select, `orderColumn('sort_order')` for drag-drop reorder; relationship `images`.
- **`Tables/MentorsTable.php`**: name (searchable, wrap), price `money('IDR')`, expertise count/chips, `is_active` badge (active=success, inactive=gray), sort_order, created_at; defaultSort `sort_order` asc.
- Slug handling: `mutateFormDataBeforeCreate/Update` sets `slug = Str::slug($data['name'])` (School/Article pattern) — belt-and-suspenders with the model hook.

**`app/Filament/Resources/MentorBookings/MentorBookingResource.php`** — nav `heroicon-o-credit-card`, group `'Bimbel'`, sort 2; record route key = id; view-only intent like DonationResource.

- **`Schemas/MentorBookingForm.php`**: everything `disabled()` except `status` Select (pending/paid/failed/expired/cancelled); sections: "Klien" (name/email/whatsapp), "Booking" (mentor, kode, amount, timestamps), "Pembayaran" (xendit_id, payment_method, invoice_url as Url/LinkEntry, paid_at).
- **`Tables/MentorBookingsTable.php`**: created_at (date), code, mentor.name (searchable), client_name (searchable), client_whatsapp, `money('IDR')` amount, status badge (pending=warning, paid=success, failed=danger, expired=gray, cancelled=gray), invoice_url as link column; `defaultSort('created_at','desc')`; header/table action **"Export CSV"** via `streamDownload` (columns: kode, tanggal, mentor, nama, email, whatsapp, jumlah, status, xendit_id) — simplest reliable CSV in Filament v5.

**`app/Providers/Filament/AdminPanel.php`**: add `'Bimbel'` to `->navigationGroups([...])` (resources auto-discovered from `app_path('Filament/Resources')` — no registration change needed).

### 11. Sitemap + env polish

- Check how the SEO feature generates the sitemap (spatie/laravel-sitemap — look for a sitemap route/command/published config). Add `/bimbel` and active mentor detail URLs (`/bimbel/{slug}`) to the same generation path. If sitemap is a manual `Sitemap::create()` builder, add these URLs there.
- `.env.example`: add the missing Xendit keys (`XENDIT_SECRET_KEY`, `XENDIT_WEBHOOK_TOKEN`, `XENDIT_IS_PRODUCTION`) — currently absent; donations/bimbel both depend on them.

### 12. Tests (PHPUnit, `RefreshDatabase`)

Create `tests/Feature/BimbelBookingTest.php` via `php artisan make:test --phpunit BimbelBookingTest`. Cover:

1. Catalog: lists active mentors, excludes inactive.
2. `show`: 200 for active mentor, 404 for inactive/unknown slug.
3. `book`: creates booking (code generated, amount = mentor price, status pending, external_id set) + redirects to payment; validation errors for missing/invalid fields; 404 posting to inactive mentor.
4. Webhook: `postJson('/webhooks/xendit', ['external_id' => "mentor_booking_{$id}", 'status' => 'PAID', 'id' => 'xnd_123'], ['x-callback-token' => config token])` → booking paid with `paid_at` + `xendit_id`; bad token → 401; duplicate event id → booking not double-processed (mirror `XenditWebhookTest`).
5. Success self-heal: visiting success page while pending + xendit_id present flips to paid.
6. Simulate route: sets paid (set `app()->environment` or config as existing local checks do — copy donations test approach if any; else drive `markAsPaid` semantics through the route under `local`).
7. Proof: unsigned → 404; signed but not paid → 404; signed + paid → 200, `content-disposition` attachment, filename contains code.
8. Inactive mentor booking POST → 404.

Also extend nothing in `XenditWebhookTest` unless convenient — bimbel webhook coverage lives in the new test file.

### 13. Finish

- `vendor/bin/pint --dirty --format agent` (mandatory after PHP edits).
- Run: `php artisan test --compact --filter=Bimbel` then the full `php artisan test --compact` to catch regressions.
- `php artisan route:list --name=bimbel` sanity check.
- Manual smoke (local): migrate → create mentor + images in `/admin` → book via `/bimbel/{slug}` → simulasi payment → verify success page WhatsApp link + PDF download → check booking row in admin.

## Out of scope

- Homepage section, footer link, mobile bottom-nav entry.
- Booking tied to authenticated users (guest checkout only, no `user_id`).
- Webhook handling of `invoice.expired` events (statuses set manually in admin).
- Legacy `POST /bimbel/xendit-callback` route.
- Mentor availability/scheduling, chat, reviews, categories/filtering beyond the catalog grid.
- Separate `MentorImageResource` (Repeater covers admin requirements).

## Risks / notes for implementer

- **Filament v5 APIs**: copy exact field/component namespaces from `CampaignForm`/`DonationsTable` (`Filament\Schemas\Schema`, `Filament\Schemas\Components\Section`, `Filament\Tables\Columns\...`) — do not use v3 `Filament\Forms\...` documentation from memory.
- **`code` uniqueness**: unique index; generation collision retry is acceptable; keep generation in the `creating` hook only.
- **external_id timing**: booking id doesn't exist until after insert — set `external_id` immediately after `create()` inside `BimbelService::createBooking()`.
- **Mock Xendit mode**: simulasi route is the dev path; keep the `app()->environment('local','development')` guard in `payment.blade.php` exactly like donations.
- **Route order**: `/bimbel/{slug}` vs `/bimbel/pembayaran/...` — different segment counts; no conflict, but register the group in the order shown to match donations conventions.
- **Signed proof URLs in WhatsApp**: permanent signed URLs (no expiry) so the mentor can still open the proof later — matches `URL::signedRoute` used by `proof.print`.
