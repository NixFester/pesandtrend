<?php

namespace App\Console\Commands;

use App\Models\Mentor;
use App\Models\School;
use App\Models\SeoCity;
use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;
use Spatie\Sitemap\Sitemap;
use Spatie\Sitemap\Tags\Url;

#[Signature('app:generate-sitemap')]
#[Description('Generate XML sitemap for the website')]
class GenerateSitemap extends Command
{
    public function handle(): int
    {
        $this->info('Generating sitemap...');

        $sitemap = Sitemap::create();

        // Homepage
        $sitemap->add(
            Url::create(route('home'))
                ->setLastModificationDate(now())
                ->setChangeFrequency(Url::CHANGE_FREQUENCY_DAILY)
                ->setPriority('1.0')
        );

        // Main listing pages
        $sitemap->add(
            Url::create(route('schools.index'))
                ->setLastModificationDate(now())
                ->setChangeFrequency(Url::CHANGE_FREQUENCY_WEEKLY)
                ->setPriority('0.9')
        );

        // Bimbel Online listing
        $sitemap->add(
            Url::create(route('bimbel.index'))
                ->setLastModificationDate(now())
                ->setChangeFrequency(Url::CHANGE_FREQUENCY_WEEKLY)
                ->setPriority('0.8')
        );

        // SEO: Sekolah Terbaik
        $sitemap->add(
            Url::create(route('seo.schools.best'))
                ->setLastModificationDate(now())
                ->setChangeFrequency(Url::CHANGE_FREQUENCY_WEEKLY)
                ->setPriority('0.8')
        );

        // SEO: Pesantren Terbaik
        $sitemap->add(
            Url::create(route('seo.pesantren.best'))
                ->setLastModificationDate(now())
                ->setChangeFrequency(Url::CHANGE_FREQUENCY_WEEKLY)
                ->setPriority('0.8')
        );

        // City-specific SEO pages
        $cities = SeoCity::active()->get();
        foreach ($cities as $city) {
            // Schools city page
            $sitemap->add(
                Url::create(route('seo.schools.city', $city->slug))
                    ->setLastModificationDate($city->updated_at)
                    ->setChangeFrequency(Url::CHANGE_FREQUENCY_WEEKLY)
                    ->setPriority('0.7')
            );

            // Pesantren city page
            $sitemap->add(
                Url::create(route('seo.pesantren.city', $city->slug))
                    ->setLastModificationDate($city->updated_at)
                    ->setChangeFrequency(Url::CHANGE_FREQUENCY_WEEKLY)
                    ->setPriority('0.7')
            );
        }

        // Individual schools
        $schools = School::published()
            ->whereNotNull('slug')
            ->select(['slug', 'updated_at'])
            ->cursor();

        foreach ($schools as $school) {
            $sitemap->add(
                Url::create(route('schools.show', $school->slug))
                    ->setLastModificationDate($school->updated_at)
                    ->setChangeFrequency(Url::CHANGE_FREQUENCY_MONTHLY)
                    ->setPriority('0.6')
            );
        }

        // Active mentors
        $mentors = Mentor::active()
            ->whereNotNull('slug')
            ->select(['slug', 'updated_at'])
            ->cursor();

        foreach ($mentors as $mentor) {
            $sitemap->add(
                Url::create(route('bimbel.show', $mentor->slug))
                    ->setLastModificationDate($mentor->updated_at)
                    ->setChangeFrequency(Url::CHANGE_FREQUENCY_WEEKLY)
                    ->setPriority('0.7')
            );
        }

        // Write sitemap
        $sitemap->writeToFile(public_path('sitemap.xml'));

        $this->info('Sitemap generated successfully at public/sitemap.xml');

        return Command::SUCCESS;
    }
}
