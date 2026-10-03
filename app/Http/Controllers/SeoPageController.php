<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\SeoCity;
use Illuminate\View\View;

class SeoPageController extends Controller
{
    public function schoolsBest(): View
    {
        $cities = SeoCity::active()->sorted()->get();

        $metaTitle = 'Sekolah Islam Terbaik di Indonesia | Pesantrends';
        $metaDescription = 'Temukan daftar sekolah Islam terbaik dan terintegrasi di Indonesia. Pilihan sekolah berkualitas dengan fasilitas lengkap dan tenaga pengajar profesional.';

        \SEOMeta::setTitle($metaTitle);
        \SEOMeta::setDescription($metaDescription);
        \SEOMeta::addKeyword(['sekolah Islam terbaik', 'sekolah terbaik Indonesia', 'SDIT terbaik', 'SMPIT terbaik', 'SMA Islam terbaik']);

        \OpenGraph::setTitle($metaTitle);
        \OpenGraph::setDescription($metaDescription);
        \OpenGraph::setUrl(route('seo.schools.best'));
        \OpenGraph::addProperty('type', 'website');

        \JsonLd::setType('WebSite');
        \JsonLd::addValue('name', 'Pesantrends');
        \JsonLd::addValue('url', route('home'));

        return view('seo-pages.schools-best', [
            'cities' => $cities,
            'pageType' => 'schools',
        ]);
    }

    public function schoolsBestByCity(string $city): View
    {
        $seoCity = SeoCity::where('slug', $city)->active()->firstOrFail();

        $title = $seoCity->meta_title ?? "Sekolah Islam Terbaik di {$seoCity->name} | Pesantrends";
        $description = $seoCity->meta_description ?? "Temukan sekolah Islam terbaik di {$seoCity->name}. Pilihan sekolah berkualitas untuk pendidikan anak Anda.";

        \SEOMeta::setTitle($title);
        \SEOMeta::setDescription($description);
        \SEOMeta::addKeyword([
            "sekolah Islam terbaik {$seoCity->name}",
            "SDIT {$seoCity->name}",
            "SMPIT {$seoCity->name}",
            "SMA Islam {$seoCity->name}",
            "sekolah terbaik {$seoCity->name}",
        ]);

        \OpenGraph::setTitle($title);
        \OpenGraph::setDescription($description);
        \OpenGraph::setUrl(route('seo.schools.city', $city));
        \OpenGraph::addProperty('type', 'website');

        \JsonLd::setType('WebPage');
        \JsonLd::addValue('name', $title);
        \JsonLd::addValue('description', $description);
        \JsonLd::addValue('url', route('seo.schools.city', $city));

        // FAQ Schema
        $faqSchema = [
            '@context' => 'https://schema.org',
            '@type' => 'FAQPage',
            'mainEntity' => [
                [
                    '@type' => 'Question',
                    'name' => "Apa sekolah Islam terbaik di {$seoCity->name}?",
                    'acceptedAnswer' => [
                        '@type' => 'Answer',
                        'text' => "Pesantrends menyediakan daftar sekolah Islam terbaik di {$seoCity->name} dengan informasi lengkap tentang fasilitas, biaya, dan testimoni.",
                    ],
                ],
                [
                    '@type' => 'Question',
                    'name' => 'Bagaimana cara memilih sekolah Islam yang tepat?',
                    'acceptedAnswer' => [
                        '@type' => 'Answer',
                        'text' => 'Pertimbangkan faktor seperti akreditasi, fasilitas asrama, rasio guru-siswa, dan biaya pendidikan. Pesantrends membantu Anda membandingkan sekolah.',
                    ],
                ],
            ],
        ];
        \JsonLd::addValue('faqs', $faqSchema);

        $schools = $seoCity->citySchools()
            ->with('school')
            ->sorted()
            ->get()
            ->pluck('school')
            ->filter();

        return view('seo-pages.schools-city', [
            'seoCity' => $seoCity,
            'schools' => $schools,
            'pageType' => 'schools',
        ]);
    }

    public function pesantrenBest(): View
    {
        $cities = SeoCity::active()->sorted()->get();

        $metaTitle = 'Pesantren Terbaik di Indonesia | Pesantrends';
        $metaDescription = 'Temukan daftar pesantren dan pondok pesantern terbaik di Indonesia. Pilihan pesantren berkualitas dengan program pendidikan dan pengasuhan yang komprehensif.';

        \SEOMeta::setTitle($metaTitle);
        \SEOMeta::setDescription($metaDescription);
        \SEOMeta::addKeyword(['pesantren terbaik', 'pondok pesantren terbaik', 'pesantren gratis', 'pesantren tahfidz', 'pesanteren kilat']);

        \OpenGraph::setTitle($metaTitle);
        \OpenGraph::setDescription($metaDescription);
        \OpenGraph::setUrl(route('seo.pesantren.best'));
        \OpenGraph::addProperty('type', 'website');

        \JsonLd::setType('WebSite');
        \JsonLd::addValue('name', 'Pesantrends');
        \JsonLd::addValue('url', route('home'));

        return view('seo-pages.pesantren-best', [
            'cities' => $cities,
            'pageType' => 'pesantren',
        ]);
    }

    public function pesantrenBestByCity(string $city): View
    {
        $seoCity = SeoCity::where('slug', $city)->active()->firstOrFail();

        $title = $seoCity->meta_title ?? "Pesantren Terbaik di {$seoCity->name} | Pesantrends";
        $description = $seoCity->meta_description ?? "Temukan pesantren dan pondok pesantern terbaik di {$seoCity->name}. Pilihan pesantren berkualitas untuk pendidikan agama dan umum.";

        \SEOMeta::setTitle($title);
        \SEOMeta::setDescription($description);
        \SEOMeta::addKeyword([
            "pesantren terbaik {$seoCity->name}",
            "pondok pesantren {$seoCity->name}",
            "pesantren tahfidz {$seoCity->name}",
            "pesantren gratis {$seoCity->name}",
        ]);

        \OpenGraph::setTitle($title);
        \OpenGraph::setDescription($description);
        \OpenGraph::setUrl(route('seo.pesantren.city', $city));
        \OpenGraph::addProperty('type', 'website');

        \JsonLd::setType('WebPage');
        \JsonLd::addValue('name', $title);
        \JsonLd::addValue('description', $description);
        \JsonLd::addValue('url', route('seo.pesantren.city', $city));

        // FAQ Schema
        $faqSchema = [
            '@context' => 'https://schema.org',
            '@type' => 'FAQPage',
            'mainEntity' => [
                [
                    '@type' => 'Question',
                    'name' => "Apa pesantren terbaik di {$seoCity->name}?",
                    'acceptedAnswer' => [
                        '@type' => 'Answer',
                        'text' => "Pesantrends menyediakan daftar pesantren terbaik di {$seoCity->name} dengan informasi lengkap tentang program pendidikan, biaya, dan fasilitas.",
                    ],
                ],
                [
                    '@type' => 'Question',
                    'name' => 'Bagaimana cara memilih pesantren yang tepat?',
                    'acceptedAnswer' => [
                        '@type' => 'Answer',
                        'text' => 'Pertimbangkan faktor seperti program pendidikan (tahfidz, umum, pondokan), fasilitas, biaya, dan lokasi. Pesantrends membantu Anda membandingkan pesantren.',
                    ],
                ],
            ],
        ];
        \JsonLd::addValue('faqs', $faqSchema);

        $schools = $seoCity->citySchools()
            ->with('school')
            ->sorted()
            ->get()
            ->pluck('school')
            ->filter();

        return view('seo-pages.pesantren-city', [
            'seoCity' => $seoCity,
            'schools' => $schools,
            'pageType' => 'pesantren',
        ]);
    }
}
