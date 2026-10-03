<?php

/**
 * @see https://github.com/artesaos/seotools
 */

return [
    'inertia' => env('SEO_TOOLS_INERTIA', false),
    'meta' => [
        /*
         * The default configurations to be used by the meta generator.
         */
        'defaults' => [
            'title' => 'Pesantrends - Temukan Sekolah & Pesantren Islam Terbaik',
            'titleBefore' => true, // Put defaults.title before page title, like 'Pesantrends - Page Title'
            'description' => 'Pesantrends membantu Anda menemukan sekolah Islam dan pesantren terbaik di Indonesia. Bandingkan biaya, fasilitas, dan informasi sekolah secara lengkap.',
            'separator' => ' | ',
            'keywords' => ['sekolah Islam', 'pesantren', 'sekolah terbaik', 'pendidikan Islam', ' Indonesia'],
            'canonical' => null, // Set to null or 'full' to use Url::full(), set to 'current' to use Url::current(), set false to total remove
            'robots' => 'index, follow', // Set to 'all', 'none' or any combination of index/noindex and follow/nofollow
        ],
        /*
         * Webmaster tags are always added.
         */
        'webmaster_tags' => [
            'google' => null,
            'bing' => null,
            'alexa' => null,
            'pinterest' => null,
            'yandex' => null,
            'norton' => null,
        ],

        'add_notranslate_class' => false,
    ],
    'opengraph' => [
        /*
         * The default configurations to be used by the opengraph generator.
         */
        'defaults' => [
            'title' => 'Pesantrends - Temukan Sekolah & Pesantren Islam Terbaik',
            'description' => 'Pesantrends membantu Anda menemukan sekolah Islam dan pesantren terbaik di Indonesia. Bandingkan biaya, fasilitas, dan informasi sekolah secara lengkap.',
            'url' => null, // Set null for using Url::current(), set false to total remove
            'type' => 'website',
            'site_name' => 'Pesantrends',
            'images' => [],
        ],
    ],
    'twitter' => [
        /*
         * The default values to be used by the twitter cards generator.
         */
        'defaults' => [
            'card' => 'summary_large_image',
            'site' => '@pesantrends',
        ],
    ],
    'json-ld' => [
        /*
         * The default configurations to be used by the json-ld generator.
         */
        'defaults' => [
            'title' => 'Pesantrends',
            'description' => 'Temukan sekolah Islam dan pesantren terbaik di Indonesia',
            'url' => null, // Set to null or 'full' to use Url::full(), set to 'current' to use Url::current(), set false to total remove
            'type' => 'WebSite',
            'images' => [],
        ],
    ],
];
