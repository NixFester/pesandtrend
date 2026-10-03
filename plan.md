# Implementation Plan: Pesantrends Feature Expansion

## Overview

Three new features to be implemented:
1. **Bantu Pesantren** - Donation & sponsorship platform for pesantren
2. **SEO Sekolah/Pesantren Terbaik** - SEO optimization & best school rankings
3. **Bimbel Online** - Online tutoring/learning platform

---

## 1. Bantu Pesantren

### Purpose
Enable donors to support pesantren financially through a platform-managed fund. The platform collects donations and distributes to schools based on verified campaign needs (asrama renovation, scholarships, mosque, etc.).

### Database Changes (Bantu Pesantren)
```
Tables to create:
- donations (id, school_id, donor_name, donor_email, amount, payment_method, status, created_at)
- campaigns (id, school_id, title, description, target_amount, current_amount, start_date, end_date, status, image)
- fund_recipients (link donations to specific needs: asrama, scholarship, mosque renovation, etc.)
```

### Database Changes (SEO Ranking)
```
Tables to create:
- seo_cities (id, name, slug, is_active, sort_order, meta_title, meta_description, created_at, updated_at)
- seo_city_schools (id, seo_city_id, school_id, sort_order, created_at)
```

### Pages
| Route | Description |
|-------|-------------|
| `/bantu-pesantren` | Campaign listing page |
| `/bantu-pesantren/{slug}` | Campaign detail with donation form |
| `/bantu-pesantren/{slug}/sukses` | Donation success confirmation |

### Components
- Campaign card
- Donation amount selector
- Payment method selection (Xendit integration already exists)
- Progress bar widget

### Admin Panel
- Campaign CRUD (Filament Resource)
- Donation list with export
- **SEO Ranking Page Management** (using artesaos/seotools + spatie/laravel-sitemap)
  - SeoCityResource: Manage cities (name, slug, meta_title, meta_description, is_active, sort_order)
  - SeoCitySchoolResource: Link schools to cities with custom sort order
  - Preview SEO meta tags per city page
  - Toggle sitemap inclusion per city

### Implementation Order
1. Create migrations for `campaigns`, `donations`, `fund_recipients`
2. Create models with relationships
3. Create DonationService
4. Create Controller and routes
5. Create Blade templates
6. Create Filament Resource
7. Create SEO Ranking Filament Resource (cities, featured schools, meta)
8. Add section to homepage

---

## 2. SEO Sekolah/Pesantren Terbaik

### Purpose
Optimize for "sekolah/pesantren terbaik" keywords and create curated ranking pages with proper SEO implementation.

### Required Composer Packages
```
composer require spatie/laravel-sitemap artesaos/seotools
```

### Package Details
1. **spatie/laravel-sitemap** - XML sitemap generation
   - Auto-crawl site or manual URL additions
   - Model integration via `Sitemapable` interface
   - Multiple sitemap files support

2. **artesaos/seotools** - SEO meta tags & structured data
   - `SEOMeta` - title, description, keywords
   - `OpenGraph` - Facebook/Social sharing
   - `TwitterCard` - Twitter cards
   - `JsonLd` - JSON-LD structured data (Organization, FAQPage, etc.)

### Pages
| Route | Description |
|-------|-------------|
| `/sekolah-terbaik` | Main landing page for "sekolah terbaik" |
| `/pesantren-terbaik` | Dedicated page for "pesantren terbaik" |
| `/sekolah-terbaik/{city}` | City-specific best schools (e.g., `/sekolah-terbaik/yogyakarta`) |
| `/pesantren-terbaik/{city}` | City-specific best pesantren |

### SEO Implementation
1. **Dynamic Meta Tags** (using artesaos/seotools)
   - Admin-customizable `meta_title` and `meta_description` per city (stored in database)
   - Default templates for ranking pages
   - Per-page override capability

2. **Structured Data (JSON-LD)** (using artesaos/seotools)
   - Add Organization schema to homepage
   - Add FAQPage schema for ranking pages
   - Add Course schema for school programs

3. **XML Sitemap** (using spatie/laravel-sitemap)
   - Generate `/sitemap.xml` with all public pages
   - Include `/sekolah-terbaik/*` routes
   - Include `/pesantren-terbaik/*` routes
   - Include `/bantu-pesantren/*` routes
   - Auto-add new schools via `Sitemapable` interface

4. **Open Graph Tags** (using artesaos/seotools)
   - Auto-generated from meta tags
   - Custom OG images per ranking page
   - Social sharing buttons

5. **Content Strategy**
   - Create article category "Sekolah/Pesantren Terbaik"
   - Auto-generate city-based landing pages
   - Admin can manage featured schools per city

### Components
- Ranking list with badges (Top 10, #1, etc.)
- SEO-optimized school cards
- City filter dropdown
- Comparison table widget
- SEO meta tag editor (in Filament admin)

### Admin Panel (Filament)
- **SeoCityResource**: Manage cities for ranking pages
  - Name, slug, is_active, sort_order
  - Custom meta_title per city
  - Custom meta_description per city
  - Featured schools selection
- **SeoCitySchoolResource**: Link schools to cities
  - School selection with search
  - Custom sort order
  - Toggle featured status

### Implementation Order
1. Install packages: `composer require spatie/laravel-sitemap artesaos/seotools`
2. Publish configs and setup
3. Create SeoCity and SeoCitySchool models
4. Create SeoCityResource and SeoCitySchoolResource for admin
5. Create SeoPageController for ranking pages
6. Setup routes with SEO meta handling
7. Create Blade templates with structured data
8. Configure sitemap generation
9. Add sitemap to robots.txt
10. Test with Google Rich Results Test

---

## 3. Bimbel Online (Mentor Marketplace)

### Purpose
Simple mentor marketplace where clients can browse available tutors/mentors, view their profiles (including capabilities like certificates and bimbel examples), make payments via Xendit, and contact via WhatsApp.

### Xendit Payment Integration (Same as Donation Flow)
Based on existing implementation in `app/Services/XenditService.php`:
1. Client fills payment form → server creates `MentorBooking` record + Xendit invoice
2. Client redirected to Xendit payment page via `invoice_url`
3. Client pays on Xendit
4. Xendit calls webhook to update payment status
5. Client redirected to success page with:
   - Downloadable payment proof (PDF)
   - WhatsApp link to mentor (pre-filled message)

### Database Changes
```
Tables to create:
- mentors (id, name, slug, tagline, description, expertise, price, whatsapp_number, is_active, sort_order, created_at, updated_at)
- mentor_images (id, mentor_id, image_path, caption, type (certificate|portfolio|example|other), sort_order, created_at)
- mentor_bookings (id, mentor_id, client_name, client_email, client_whatsapp, amount, xendit_id, external_id, invoice_url, status (pending|paid|expired|cancelled), payment_method, paid_at, created_at)
```

### Pages (Client-Facing)
| Route | Description |
|-------|-------------|
| `/bimbel` | Mentor catalog listing page |
| `/bimbel/{slug}` | Mentor detail page with gallery, price, and payment form |
| `/bimbel/{slug}/pembayaran` | Xendit redirect (handled internally) |
| `/bimbel/{slug}/sukses` | Success page with proof download + WhatsApp button |
| `/bimbel/{slug}/gagal` | Payment failed page |

### Client Flow
1. Client visits `/bimbel` → sees grid of available mentors
2. Client clicks mentor → sees detailed profile with:
   - Name, tagline, expertise
   - Photo gallery (certificates, bimbel examples, portfolios)
   - Price for tutoring service
   - WhatsApp contact button
3. Client fills payment form (name, email, WhatsApp number)
4. System creates booking + Xendit invoice, redirects to Xendit payment page
5. Client pays via Xendit
6. Xendit webhook updates booking status to "paid"
7. Client redirected to `/bimbel/{slug}/sukses`:
   - **WhatsApp button**: Opens WhatsApp chat with mentor (pre-filled message: "Halo, saya [name] sudah melakukan pembayaran bimbel via Pesantrends. Bukti: [link_to_proof]")
   - **Download Bukti Pembayaran**: Generates PDF proof of payment

### Pages (Admin Panel)
| Route | Description |
|-------|-------------|
| `/admin/bimbel/mentors` | List all mentors |
| `/admin/bimbel/mentors/create` | Create new mentor |
| `/admin/bimbel/mentors/{id}/edit` | Edit mentor details & images |
| `/admin/bimbel/bookings` | View all booking/payments |
| `/admin/bimbel/bookings/{id}` | View booking details |

### Admin Features
- CRUD mentors with all details
- Upload multiple images per mentor (drag & drop)
- Image categorization (certificate, portfolio, bimbel example, other)
- Reorder images via drag & drop
- View incoming bookings and payment status
- View Xendit invoice details
- Export booking list to CSV

### Components
- Mentor card (photo, name, tagline, price, expertise tags)
- Mentor gallery (lightbox for images)
- Payment form (name, email, WhatsApp)
- Payment proof PDF (styled HTML → PDF)
- WhatsApp button (pre-filled message with client name)
- Image uploader with preview and caption

### XenditService Addition
Add new method `createMentorBookingInvoice(MentorBooking $booking): array`
- Similar to `createDonationInvoice()` pattern
- External ID format: `mentor_booking_{id}`
- Description: "Pembayaran Bimbel - {mentor_name}"
- Success redirect: `/bimbel/{slug}/sukses`
- Failure redirect: `/bimbel/{slug}/gagal`

### Webhook Handler Addition
Update `handleWebhook()` in XenditService to process `mentor_booking_*` external IDs:
- Update `MentorBooking` status to "paid"
- Store `paid_at` timestamp

### Implementation Order
1. Create migrations for `mentors`, `mentor_images`, `mentor_bookings`
2. Create models with relationships
3. Add `createMentorBookingInvoice()` method to XenditService
4. Update webhook handler for mentor bookings
5. Create BimbelController with catalog and payment handling
6. Create payment proof PDF generation (reuse proof.blade.php pattern)
7. Create Blade templates
8. Create Filament Resources for admin
9. Add WhatsApp deep link integration
10. Add section to homepage

---

## Implementation Phases

### Phase 1: Foundation (Start Here)
- Install SEO packages: `composer require spatie/laravel-sitemap artesaos/seotools`
- Create all migrations (campaigns, donations, mentors, seo_cities, etc.)
- Create all models with relationships
- Create base services

### Phase 2: Public Pages
- Bantu Pesantren pages
- SEO ranking pages
- Bimbel catalog pages

### Phase 3: Admin Panel
- Filament Resources for all new entities
- Dashboard widgets

### Phase 4: Integration
- Add to homepage sections
- Add to navbar/navigation
- Sitemap updates

### Phase 5: Testing & Polish
- Mobile responsiveness
- SEO validation
- Payment flow testing

---

## File Structure

```
app/
├── Http/Controllers/
│   ├── DonationController.php      # Bantu Pesantren
│   ├── SeoPageController.php       # SEO Ranking pages
│   ├── BimbelController.php        # Mentor Marketplace
│   └── ...
├── Models/
│   ├── Campaign.php               # Bantu Pesantren
│   ├── Donation.php               # Bantu Pesantren
│   ├── Mentor.php                 # Bimbel Online
│   ├── MentorImage.php            # Bimbel Online
│   ├── MentorBooking.php         # Bimbel Online
│   ├── SeoCity.php               # SEO Ranking
│   ├── SeoCitySchool.php         # SEO Ranking
│   └── ...
├── Services/
│   ├── DonationService.php
│   └── BimbelService.php         # Bimbel booking logic

database/migrations/
├── create_campaigns_table.php
├── create_donations_table.php
├── create_mentors_table.php
├── create_mentor_images_table.php
├── create_mentor_bookings_table.php
├── create_seo_cities_table.php
└── create_seo_city_schools_table.php

resources/views/
├── donations/
│   ├── index.blade.php
│   ├── show.blade.php
│   └── success.blade.php
├── seo-pages/
│   ├── schools-best.blade.php
│   └── pesantren-best.blade.php
├── bimbel/
│   ├── index.blade.php           # Mentor catalog
│   ├── show.blade.php            # Mentor detail + gallery
│   ├── success.blade.php         # Success with WhatsApp + proof download
│   └── failed.blade.php          # Payment failed
└── ...

app/Filament/Resources/
├── CampaignResource.php
├── DonationResource.php
├── MentorResource.php
├── MentorImageResource.php
├── MentorBookingResource.php
├── SeoCityResource.php
└── SeoCitySchoolResource.php
```

---

## Dependencies & External Services

- **Xendit** (already integrated) - used for all payments (Bantu Pesantren donations + Bimbel bookings)
  - Invoice API for creating payment pages
  - Webhook for payment confirmation
  - Reuses existing `app/Services/XenditService.php`
- **spatie/laravel-sitemap** - XML sitemap generation
- **artesaos/seotools** - SEO meta tags, Open Graph, Twitter Cards, JSON-LD structured data
- **WhatsApp** - deep links for direct messaging (no external API needed)
- No new composer packages needed

---

## Priority Recommendation

1. **Bantu Pesantren** - Quick wins, clear use case
2. **SEO Sekolah/Pesantren Terbaik** - Immediate traffic impact
3. **Bimbel Online** - Simple mentor marketplace, moderate complexity (simpler than original plan)

---

## Questions to Clarify

1. ~~Should donations go directly to schools or through a pooled fund?~~ **ANSWERED: Pooled fund**
2. ~~What payment methods for donations?~~ **ANSWERED: Xendit channels**
3. ~~For Bimbel: Self-hosted video or YouTube/Vimeo embed?~~ **ANSWERED: Simplified to mentor marketplace (no video)**
4. ~~Should courses be exclusive to partnered schools or open to all?~~ **ANSWERED: Platform-only content (mentors managed by admin)**

### Design Decisions (from client answers)
- **Donations**: Pooled fund model - platform collects, then distributes to schools based on campaign needs
- **Bimbel**: Mentor marketplace with Xendit payment - clients pay via Xendit gateway, then contact mentor via WhatsApp
- **Mentor images**: Admin uploads photos (certificates, portfolio, bimbel examples) per mentor
- **Payment flow**: Client fills form → Xendit payment → webhook updates status → success page with proof + WhatsApp link
- **WhatsApp message**: Pre-filled with client name and link to downloadable proof
- **SEO Ranking**: Admin-managed cities with featured schools, custom meta per city page
