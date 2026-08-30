# Pesantrends — Admin-Managed Catalog, Admin/Parent Onboarding, Mobile-First UX

## Context

`D:\kerja\pesantrends` is a Laravel 13.17 / PHP ^8.3 / SQLite / Tailwind 4 / Vite 8 app running **Flow 1**: public browsing, search/sort, compare, cost calculator, articles, newsletter, save-to-dashboard. Today login is decorative — there is **no role column**, the seeded `admin@pesantrends.id` is functionally identical to a parent, there is no admin panel, no payments, no applications, and no tests beyond stubs.

The user wants the system elevated so that:

- **Browse side (Flow 1) is mobile-first**: persistent bottom navigation, distance-sorted school list using GPS, a Leaflet/OSM map of schools, and a WhatsApp `wa.me` contact CTA on each school. Public users can save schools and create an account without forcing it.
- **Admin manages every catalog asset** from a Filament panel: schools (incl. GPS, WhatsApp, fees, photos, admission status), facilities, programs, achievements, articles, testimonials, homepage stats, plus a **bulk CSV import** for schools.
- **Flow 2 onboarding** supports **both admin-entered and parent-entered applications** through one shared domain. Either path produces an `Application` row with NIK, student + parent fields, document uploads; the same service creates an Xendit invoice that charges `registration_fee + uang_pangkal + spp_monthly`; the same printable proof of payment serves both. Admin can also record a manual payment if the family pays cash/transfer.
- **Roles are real**: `parent` (default) and `admin`. Admins get a Filament panel; parents get a dashboard with their applications, payment status, and downloadable proof.

The plan **preserves the existing fee column names** (`uang_pangkal`, `spp_monthly`, `asrama_monthly`, `seragam_fee`, `ekskul_fee`, `study_tour_fee`) so the existing calculator and `schools.show` deep-links keep working. New columns are additive and nullable.

The school-detail page now offers **two CTAs**: "Ajukan Pendaftaran" (primary, opens the parent wizard or the admin create-modal depending on who is signed in) and "Chat via WhatsApp" (the existing `wa.me` behavior, kept as the secondary action so the browse-and-chat flow still works).

---

## High-Level Architecture

**Stack additions** (all new, all additive):
- `filament/filament:^5.0` — verified compatible with Laravel 13/PHP 8.3.
- `livewire/livewire:^4.0` — bundles with Filament 5; the parent wizard uses it directly.
- `spatie/laravel-permission` — `admin` and `parent` roles.
- `xendit/xendit-php` (official SDK, current major) — hosted invoice + callback; if the package's PHP requirement lags, fall back to a `XenditClient` interface backed by Laravel `Http::post('https://api.xendit.co/v2/invoices', ...)`.
- `maatwebsite/excel:^3.1` — CSV bulk import.
- `spatie/laravel-pdf` + `spatie/browsershot` for production-quality A4 PDFs; fallback to `barryvdh/laravel-dompdf` when Chromium is unavailable (local/CI).
- NPM `leaflet@^1.9` (lazy-loaded chunk for map pages).

**DB**: SQLite stays for dev. Every new column is nullable and every migration is additive — no edits to the existing `2026_08_27_*` migration.

**Storage**: `storage/app/private/applications/...` for documents (private disk). Downloads go through `URL::temporarySignedRoute(...)` only.

**Mobile-first shell**: existing top navbar stays; a new `<x-mobile-bottom-nav>` is rendered below the footer in `resources/views/layouts/app.blade.php`, hidden on `md+`.

**Single source of truth — no duplicate forms**:
- `App\Domain\Onboarding\ApplicationStatus` enum + transition table.
- `App\Services\ApplicationService` with one transactional method per use case (`createDraft`, `submit`, `attachDocuments`, `verify`, `reject`, `createPayment`, `markPaid`, `markCompleted`, `printProof`). Both Filament admin resource and Livewire parent wizard call it.
- `App\Http\Requests\ApplicationRequest` consumed by both UIs (Filament via `getFormSchema()`, Livewire via `app(ApplicationRequest::class)->rules()`).
- `App\ViewModels\ApplicationView` shared DTO used by Filament infolist, parent dashboard, and printable proof.

**Money rule** (no invented fee columns): the Xendit invoice always equals `registration_fee + uang_pangkal + spp_monthly`, stored verbatim in `application_payments.breakdown_json`.

---

## File-by-File Plan

### 1. Migrations (additive, all nullable / indexed where needed)

- **`2026_08_28_000001_add_role_to_users_table.php`** — `role` (string default `parent`, indexed), `phone`, `whatsapp`, `address`, `lat`, `lng` (decimal 10,7), `geocoded_at`.
- **`2026_08_28_000002_add_admin_metadata_to_schools_table.php`** — `whatsapp_e164`, `whatsapp_label`, `latitude`, `longitude`, `is_published` (bool default true), `admission_status` enum (`open|closed|waitlist`), `admission_notes` (text), `meta_title`, `meta_description`. All nullable; existing rows load unchanged.
- **`2026_08_28_000003_create_school_photos_table.php`** — id, school_id, path, caption, sort.
- **`2026_08_28_000004_create_applications_table.php`** — id, `public_id` (ULID, unique), `school_id`, `created_by_user_id` (nullable — admin or parent), `parent_user_id` (nullable — guest application possible), `parent_name`, `parent_email`, `parent_phone`, `parent_whatsapp`, `student_name`, `student_nik_encrypted` (encrypted cast), `student_nik_hash` (HMAC, indexed for uniqueness), `student_gender`, `student_birth_place`, `student_birth_date`, `student_address`, `previous_school`, `target_jenjang`, `notes`, `status` (enum, indexed), `submitted_at`, `verified_by_user_id`, `verified_at`, `rejection_reason`, `created_payment_id`, timestamps. Indexes: `(school_id, status)`, `public_id`, `student_nik_hash`.
- **`2026_08_28_000005_create_application_documents_table.php`** — id, `application_id`, `kind` enum (`kk|akta|rapor|photo|other`), `original_name`, `path` (private disk), `mime`, `size`, `uploaded_by_user_id`, timestamps.
- **`2026_08_28_000006_create_application_payments_table.php`** — id, `application_id`, `provider` (`xendit|manual`), `external_id` (unique nullable — Xendit invoice id), `idempotency_key` (unique), `invoice_url`, `amount`, `breakdown_json` (json), `status` enum (`pending|paid|expired|failed`), `payment_method`, `paid_at`, `raw_callback` (json nullable), `recorded_by_user_id`, timestamps.
- **`2026_08_28_000007_create_xendit_webhook_events_table.php`** — id, `event_id` (Xendit `x-callback-event-id`, unique), `event_type`, `payload` (json), `processed_at`, timestamps.
- **`2026_08_28_000008_create_homepage_settings_table.php`** — singleton: stats counters and homepage hero overrides.
- **`2026_08_28_000009_create_role_tables.php`** — Spatie permission's published tables.

### 2. Models

- **`app/Models/User.php`** — fillable adds `phone, whatsapp, address, lat, lng`. `casts` include `'role' => 'string'`, `geocoded_at`. `HasRoles` trait. `FilamentUser` contract with `canAccessPanel()` returning `$this->hasRole('admin')`. Relations: `applicationsCreated()`, `applicationsParent()`, `schoolPhotos()`.
- **`app/Models/School.php`** — append new columns to fillable + casts; relations `photos()`, `applications()`. Scope `scopeNearby($q, $lat, $lng, $radiusKm = 50)` using a portable Haversine `selectRaw`. Scope `scopePublished()`. Accessor `getWhatsappHrefAttribute()` returning `https://wa.me/<digits>?text=...`. Extend `scopeSorted` with `'terdekat'` (requires coords; falls back to default).
- **New `app/Models/SchoolPhoto.php`** — `HasMany` back to school.
- **New `app/Models/Application.php`** — `$guarded = []`. `$hidden` includes `student_nik_encrypted`, `student_nik_hash`. `casts` for encrypted NIK + status enum. Relations: `school()`, `documents()`, `payments()`, `latestPayment()`, `createdBy()`, `parentUser()`, `verifiedBy()`.
- **New `app/Models/ApplicationDocument.php`** — BelongsTo application.
- **New `app/Models/ApplicationPayment.php`** — BelongsTo application; `isPaid()`, `statusLabel()` helpers.
- **New `app/Models/XenditWebhookEvent.php`** — idempotency log.
- **New `app/Models/HomepageSetting.php`** — singleton `current()` helper.

### 3. Domain & Enums

- **`app/Domain/Onboarding/ApplicationStatus.php`** — backed enum: `Draft, Submitted, DocumentReview, Verified, Rejected, PaymentPending, Paid, Cancelled, Completed`. Public `canTransitionTo(self $next): bool` enforces allowed edges; webhook may only flip `PaymentPending → Paid`.
- **`app/Domain/Onboarding/DocumentKind.php`** — backed enum for the doc upload kind.

### 4. Services

- **`app/Services/ApplicationService.php`** — transactional methods (`createDraft`, `update`, `submit`, `attachDocuments`, `verify`, `reject`, `createPayment`, `markPaid`, `markCompleted`). Builds `breakdown_json = ['registration_fee' => cfg, 'uang_pangkal' => school, 'spp_first_month' => school]`. Uses `DB::transaction`.
- **`app/Services/XenditService.php`** — wraps SDK or `Http::post`. Methods `createInvoice(ApplicationPayment $p): array`, `handleWebhook(Request $r): void` (verifies `x-callback-token` constant-time, inserts into `xendit_webhook_events` keyed by event id, marks payment paid, transitions application).
- **`app/Services/DocumentStorageService.php`** — stores uploads on private disk, generates `URL::temporarySignedRoute('documents.download', now()->addMinutes(15), ['document' => $id])`.
- **`app/Services/NearbySortService.php`** — Haversine selectRaw; used by `School::scopeNearby`.
- **`app/Services/PdfRenderer.php`** — driver choice in boot (`extension_loaded` probe + env). `proof(ApplicationPayment $p)` returns binary via Spatie Browsershot or Dompdf fallback.
- **`app/Services/SchoolsImportService.php`** — wraps Maatwebsite import + dry-run preview + failed-rows download.

### 5. Policies & Middleware

- **`app/Policies/ApplicationPolicy.php`** — `view, update, verify, reject, createPayment`: parent (own only) or admin (any).
- **`app/Policies/SchoolPolicy.php`** — public `view` requires `is_published`; `update, delete`: admin only.
- **`app/Policies/ApplicationDocumentPolicy.php`** — applicant, admin, or school-of-record can download.
- **`bootstrap/app.php`** — register middleware aliases `role:admin`, `ensure.parent`; CSRF except-list adds `webhook/xendit`; configure `throttle:30,1` on the webhook.

### 6. Auth & Routing

- **`app/Http/Controllers/AuthController.php`** — extend: after successful login, `if ($user->hasRole('admin')) redirect('/admin'); else redirect('/dashboard');`. Register flow assigns `parent` role by default. Add `throttle:5,1` to login/register routes.
- **Admin uses Filament's own `/admin/login`** (built-in). The public `/masuk` route serves parents only.
- **`routes/web.php`** — additive:
  - Public: `GET /sekolah` extended with `?lat&lng&view=map`; `GET /peta` full-screen map; `GET /daftar-sekolah/{school}` entry; `GET /bukti-pembayaran/{payment}/cetak` (signed); `GET /dokumen/{document}/unduh` (signed).
  - Parent (auth + role parent): `GET /orang-tua/pendaftaran`, `GET /orang-tua/pendaftaran/{application}`, `GET /orang-tua/pendaftaran/{application}/bayar`.
  - Admin: `/admin/*` via Filament.
  - Webhook: `POST /webhook/xendit` outside the `web` middleware group; signed-drop in CSRF.

### 7. Filament Admin Panel (`/admin`)

- **`app/Providers/Filament/AdminPanelProvider.php`** — wires Vite entry `resources/css/admin.css` so Filament's preflight doesn't collide with the public Tailwind 4 `@theme` block.
- Resources (`app/Filament/Resources/`):
  - **`SchoolResource.php`** — sections: Identity, Location (lat/lng with mini Leaflet picker), Admission (`admission_status`, `registration_open`, notes), Fees (read-only — admin edits the existing fee columns via dedicated numeric fields, never renames them), Programs (BelongsToMany), Facilities (BelongsToMany), Achievements (HasMany repeater), Tags (json), Photos (`SchoolPhotoRelationManager`), WhatsApp, Publishing, Stats. Header action: "Impor CSV".
  - **`ArticleResource.php`** — Tiptap rich text.
  - **`TestimonialResource.php`**.
  - **`FacilityResource.php`** + **`ProgramResource.php`**.
  - **`OnboardingApplicationResource.php`** — list with status/school/payment filters. Edit page tabs: Biodata, Dokumen (uploads via `DocumentStorageService`), Verifikasi (verify/reject with reason), Pembayaran (create Xendit invoice or "Catat Manual"), Cetak. Infolist reuses `ApplicationView` DTO.
  - **`ApplicationPaymentResource.php`** — read-only with status + invoice link + "Buka bukti" (opens signed proof URL).
  - **`HomepageSettingResource.php`** — singleton page.
  - **`SubscriberResource.php`** — list + export CSV.
  - Pages: **`ImportSchools`** (CSV upload → preview → commit).
- Widgets: `StatsOverviewWidget` (applications today, paid count, revenue this month, pending).
- Access: gated by `User::canAccessPanel` returning `$this->hasRole('admin')`.

### 8. Livewire Components (parent self-service)

- **`app/Livewire/Onboarding/ApplyWizard.php`** — 5-step stepper (Sekolah → Biodata Siswa → Biodata Orang Tua → Upload Dokumen → Review & Bayar). Uses `ApplicationRequest` rules. Submits through `ApplicationService`. Step 5 generates `ApplicationPayment` and redirects to `/orang-tua/pendaftaran/{id}/bayar`.
- **`app/Livewire/Onboarding/MyApplications.php`** — list with status badges, latest payment, link to detail.
- **`app/Livewire/Onboarding/ApplicationDetail.php`** — status timeline, dokumen preview via signed URL, tombol bayar/ulangi bayar.
- **`resources/views/livewire/onboarding/*.blade.php`** — Tailwind 4 + green stepper, native mobile date/file inputs.

### 9. Blade Views & Mobile UX

- **`resources/views/components/mobile-bottom-nav.blade.php`** — fixed bottom bar (Beranda, Cari, Simpan, Pendaftaran Saya, Akun) using existing `<x-icon>`; `md:hidden`.
- **`resources/views/layouts/app.blade.php`** — include `<x-mobile-bottom-nav>` after the footer.
- **`resources/views/schools/show.blade.php`** — replace the cost-card buttons with a **pair of CTAs**:
  - Primary green: "Ajukan Pendaftaran" → `route('daftar-sekolah', $school)`. If `auth()->guest()`, redirects to `/masuk?next=...` and returns.
  - Outline: "Chat via WhatsApp" → `wa.me` deeplink from `$school->whatsapp_href`. The calculator deep-link and save button stay.
  - Adds the Leaflet mini-map and "Buka di Google Maps" link when lat/lng exist.
- **`resources/views/schools/index.blade.php`** — view toggle "Daftar / Peta"; sort toggle "Terdekat / Rating / Termurah" using `scopeSorted`; "Gunakan lokasi saya" button using `navigator.geolocation.getCurrentPosition` writing to the URL.
- **`resources/views/schools/nearby.blade.php`** — full-screen Leaflet.
- **`resources/views/payments/proof.blade.php`** — A4 Blade template (school header, applicant details, code, breakdown table, status stamp, "Terbayar pada").
- **`resources/views/components/flash.blade.php`** — extend with `profile_success`, `application_submitted`, `payment_success`, `application_rejected`.
- **`resources/css/admin.css`** — new Vite entry for Filament; mirrors forest/gold tokens without clashing with the public Tailwind 4 layer.
- **`resources/js/map.js`** — Leaflet init, lazy-imported by map pages.
- **`vite.config.js`** — add `admin.css` and dynamic `import('./map.js')` chunks.

### 10. Console

- `app/Console/Commands/MakeAdminCommand.php` — `php artisan pesantrends:make-admin {email}`.
- `app/Console/Commands/SchoolsImportCommand.php` — `php artisan schools:import {file.csv}` (used by Filament Import page internally too).
- `app/Console/Commands/XenditReconcileCommand.php` — hourly reconcile via `/v2/invoices/{id}` for stale pending payments; scheduled in `routes/console.php`.

### 11. Config & Env

- `config/services.php` — `xendit` block (`secret_key`, `webhook_token`, `is_production`).
- `config/payments.php` — `registration_fee` (IDR, default 250000; overridable later per school).
- `.env.example` — `XENDIT_SECRET_KEY`, `XENDIT_WEBHOOK_TOKEN`, `XENDIT_IS_PRODUCTION=false`, `BROWSERSHOT_NODE_PATH`, `BROWSERSHOT_CHROMIUM_PATH`, `MAP_TILE_URL=https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png`.

### 12. Seeders

- **`database/seeders/PesantrendsRoleSeeder.php`** (new) — creates `admin` and `parent` roles; assigns `admin` role to `admin@pesantrends.id` and `admin@gmail.com`; keeps `user@pesantrends.id` as `parent`.
- **`PesantrendsSeeder.php`** — extended to: backfill new `schools` columns (lat/lng/whatsapp_e164) from a static map; seed `HomepageSetting::current()`.

### 13. Tests (`tests/Feature/` + `tests/Unit/`)

- `ApplicationSubmitTest` — parent creates draft → submits → status = Submitted.
- `ApplicationVerifyTest` — admin verifies → status = Verified.
- `ApplicationPaymentTest` — fake `XenditService` bound in container; asserts breakdown keys exactly `registration_fee`, `uang_pangkal`, `spp_first_month` and that no extra fee names appear.
- `XenditWebhookIdempotencyTest` — same payload twice → one DB transition.
- `SchoolCsvImportTest` — uses `Excel::fake()`; valid rows committed, invalid rows skipped with errors.
- `PolicyTest` — parent cannot view another family's application; admin can.
- `SignedUrlTest` — documents require signature; expired links return 403.
- `NearbySortTest` — `School::scopeNearby` returns expected order for known coords.
- `ProofPdfRenderTest` — `PdfRenderer::proof` returns non-empty binary for a paid payment.
- `RoleRedirectTest` — admin login → `/admin`; parent login → `/dashboard`.
- `Unit/ApplicationStatusEnumTest` — transitions enforced.
- `Unit/SchoolWhatsappHrefTest` — strips non-digits; builds correct `wa.me` URL.

### 14. Critical Files To Be Modified (summary)

- **New** (~45 files):
  - `app/Domain/Onboarding/{ApplicationStatus,DocumentKind}.php`
  - `app/Models/{Application,ApplicationDocument,ApplicationPayment,XenditWebhookEvent,SchoolPhoto,HomepageSetting}.php`
  - `app/Services/{ApplicationService,XenditService,DocumentStorageService,NearbySortService,PdfRenderer,SchoolsImportService}.php`
  - `app/Policies/{ApplicationPolicy,SchoolPolicy,ApplicationDocumentPolicy}.php`
  - `app/Filament/Resources/{School,Article,Testimonial,Facility,Program,OnboardingApplication,ApplicationPayment,HomepageSetting,Subscriber}Resource.php`
  - `app/Filament/Pages/ImportSchools.php`
  - `app/Filament/Widgets/StatsOverviewWidget.php`
  - `app/Providers/Filament/AdminPanelProvider.php`
  - `app/Livewire/Onboarding/{ApplyWizard,MyApplications,ApplicationDetail}.php`
  - `app/Console/Commands/{MakeAdminCommand,SchoolsImportCommand,XenditReconcileCommand}.php`
  - `app/Imports/SchoolsImport.php`
  - `app/Http/Controllers/{Onboarding,Account,Proof,DocumentDownload,XenditWebhook}Controller.php`
  - `app/Http/Requests/{AccountUpdateRequest,ApplicationRequest}.php`
  - `database/migrations/2026_08_28_*` (9 files)
  - `database/seeders/PesantrendsRoleSeeder.php`
  - `resources/views/livewire/onboarding/*.blade.php`
  - `resources/views/payments/proof.blade.php`
  - `resources/views/schools/nearby.blade.php`
  - `resources/views/components/mobile-bottom-nav.blade.php`
  - `resources/css/admin.css`, `resources/js/map.js`
  - `routes/webhooks.php` (Xendit route file)

- **Edited** (surgical):
  - `composer.json` — add the new packages.
  - `package.json` — `leaflet`.
  - `vite.config.js` — add `admin.css` entry and dynamic map chunk.
  - `bootstrap/app.php` — middleware aliases, CSRF except-list, webhook route loading.
  - `routes/web.php` — append public/parent/welcome routes.
  - `config/auth.php` — unchanged structurally; permission package handles roles.
  - `resources/views/layouts/app.blade.php` — include bottom nav.
  - `resources/views/schools/show.blade.php` — CTA pair + mini-map.
  - `resources/views/schools/index.blade.php` — sort toggle + view toggle.
  - `resources/views/components/flash.blade.php` — new flash keys.
  - `app/Models/{User,School}.php` — fillable, casts, scopes.
  - `app/Http/Controllers/AuthController.php` — role-aware redirect.
  - `database/seeders/PesantrendsSeeder.php` — backfill lat/lng/whatsapp + homepage settings.

### 15. Reused Patterns (do not reinvent)

- `School::scopeFilter` + `School::scopeSorted` (`app/Models/School.php`) — extended with `'terdekat'`; no duplication.
- `SchoolController::orderByIdsRaw($ids)` (`app/Http/Controllers/SchoolController.php`) — kept for related/recent ordering.
- `<x-icon>` (`resources/views/components/icon.blade.php`) — reused for bottom-nav icons.
- `<x-flash>` (`resources/views/components/flash.blade.php`) — extended with new keys.
- Existing `@theme` tokens (`resources/css/app.css`) — drive Filament primary color override.
- `CalculatorController` — untouched; the show page still deep-links `pangkal/spp/asrama/seragam/ekskul/tour`.

---

## Verification

1. **Install & migrate**
   - `composer require filament/filament:^5.0 livewire/livewire:^4.0 spatie/laravel-permission xendit/xendit-php spatie/laravel-pdf spatie/browsershot maatwebsite/excel:^3.1`
   - `npm i leaflet@^1.9 && npm run build`
   - `php artisan vendor:publish --tag=filament-config`
   - `php artisan migrate:fresh --seed`
   - `php artisan pesantrends:make-admin admin@pesantrends.id`
2. **Mobile shell smoke test** — `php artisan serve`, Chrome DevTools iPhone 14 viewport: bottom nav visible, top navbar collapses, no horizontal scroll.
3. **Flow 1 still works** — home → `/sekolah` → `/sekolah/{slug}` → `/kalkulator` → `/artikel` → WhatsApp CTA still opens `wa.me`. Save-to-dashboard still works.
4. **Geo sort + map** — on `/sekolah`, click "Gunakan lokasi saya"; confirm reorder and distance badge. Toggle to map view; click a marker → school detail.
5. **Admin manages catalog** — log in as admin → `/admin/schools` create a school with lat/lng/WhatsApp + the six fees (exact names), photo upload, publish toggle. Create an article, a testimonial, edit homepage stats. Upload `schools.csv` via Import page.
6. **Admin-entered application (one of two paths)**
   - `/admin/onboarding-applications` → Create → choose school → fill biodata + dokumen (uploads go to `storage/app/private/applications/...`) → Save.
   - Tab Verifikasi → Verify.
   - Tab Pembayaran → "Buat Invoice Xendit" → copy sandbox URL.
   - In Xendit dashboard simulate `PAID` → application status flips to `Paid` within seconds.
   - Tab Cetak → "Cetak Bukti" → A4 PDF downloads with school header, applicant details, breakdown `registration_fee + uang_pangkal + spp_monthly`, Xendit invoice id.
   - Repeat with "Catat Pembayaran Manual" to confirm cash path lands on the same Paid state.
7. **Parent-entered application (other path)**
   - Log out, register a new parent.
   - Open a school detail → "Ajukan Pendaftaran" → step through the Livewire wizard → submit.
   - Pay through Xendit sandbox → status flips within 4s → proof downloads.
   - Visit `/orang-tua/pendaftaran` to see status timeline + reprint proof.
8. **Webhook idempotency** — POST the same `invoice.paid` payload twice via Postman; the second call is a 200 no-op.
9. **Webhook security** — POST with wrong `x-callback-token` → 401; documents without signature → 403.
10. **Role redirect** — `admin@pesantrends.id` → `/masuk` lands on `/admin`. Parent → `/masuk` lands on `/dashboard`. Parents hitting `/admin/*` are denied.
11. **Policy enforcement** — parent A attempts `/orang-tua/pendaftaran/{parentB-application}` → 403.
12. **Automated tests** — `php artisan test` → green.

---

## Out of Scope (next pass)

- Real-time push (FCM / WebSocket).
- Recurring SPP billing via Xendit Recurring.
- i18n — app stays `id`-only.
- Replacing `AuthController` with Breeze + Livewire starter.
- Bulk email blasts (currently `MAIL_MAILER=log`).
- Per-school registration-fee override (default `config('payments.registration_fee')` applies; flagged for product decision).
