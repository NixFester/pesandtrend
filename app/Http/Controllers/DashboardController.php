<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\School;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $user = auth()->user();

        $savedSchools = $user->savedSchools()
            ->with(['facilities'])
            ->orderByPivot('created_at', 'desc')
            ->get();

        $recommended = School::whereNotIn('id', $savedSchools->pluck('id'))
            ->orderByDesc('rating')
            ->take(3)
            ->get();

        $recentIds = array_slice(session('recent_schools', []), 0, 4);
        $recentlyViewed = empty($recentIds)
            ? collect()
            : School::whereIn('id', $recentIds)
                ->orderByRaw(\App\Http\Controllers\SchoolController::orderByIdsRaw($recentIds))
                ->get();

        $articlesRead = Article::orderByDesc('views')->take(3)->get();

        return view('dashboard', [
            'user' => $user,
            'savedSchools' => $savedSchools,
            'recommended' => $recommended,
            'recentlyViewed' => $recentlyViewed,
            'articlesRead' => $articlesRead,
        ]);
    }
}
