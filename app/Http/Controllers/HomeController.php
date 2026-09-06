<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\Program;
use App\Models\School;
use App\Models\Testimonial;

class HomeController extends Controller
{
    public function index()
    {
        return view('home', [
            'featuredSchools' => School::where('is_featured', true)
                ->with(['facilities', 'programs'])
                ->orderByDesc('rating')
                ->take(3)
                ->get(),
            'flagshipPrograms' => Program::whereIn('slug', [
                'tahfidz-30-juz', 'stem-robotika', 'bahasa-arab-intensif',
                'leadership-dakwah', 'quran-science', 'digital-literacy',
            ])->get(),
            'testimonials' => Testimonial::all(),
            'articles' => Article::published()->take(3)->get(),
            'stats' => [
                'schools' => '1.240+',
                'provinces' => '34',
                'parents' => '50rb+',
            ],
        ]);
    }
}
