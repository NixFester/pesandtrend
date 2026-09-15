<?php

namespace App\Http\Controllers;

use App\Models\Application;
use App\Models\Article;
use App\Models\School;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $user = auth()->user();

        // ── Applications ──
        // Auto-link any unlinked applications by email
        Application::whereNull('parent_user_id')
            ->where('parent_email', $user->email)
            ->update(['parent_user_id' => $user->id]);

        $applications = Application::where(function ($query) use ($user) {
            $query->where('parent_user_id', $user->id)
                ->orWhere('created_by_user_id', $user->id)
                ->orWhere('parent_email', $user->email);
        })
            ->with(['school', 'latestPayment'])
            ->latest()
            ->get();

        // ── Stats ──
        $activeStatuses = ['submitted', 'document_review', 'verified', 'payment_pending'];
        $stats = [
            'total_applications' => $applications->count(),
            'active_applications' => $applications->filter(
                fn ($app) => in_array($app->status->value, $activeStatuses)
            )->count(),
            'paid_applications' => $applications->filter(
                fn ($app) => $app->latestPayment?->isPaid()
            )->count(),
        ];

        // ── Saved Schools ──
        $savedSchools = $user->savedSchools()
            ->with(['facilities'])
            ->orderByPivot('created_at', 'desc')
            ->get();

        $stats['saved_schools'] = $savedSchools->count();

        // ── Recommendations (city-based if user has saved schools) ──
        $savedIds = $savedSchools->pluck('id')->toArray();
        $savedCities = $savedSchools->pluck('city')->unique()->filter()->values();

        $recommended = School::query()
            ->whereNotIn('id', $savedIds)
            ->when(
                $savedCities->isNotEmpty(),
                fn ($q) => $q->whereIn('city', $savedCities),
                fn ($q) => $q->orderByDesc('rating')
            )
            ->orderByDesc('rating')
            ->take(3)
            ->get();

        // If city-based returns too few, fill with top-rated
        if ($recommended->count() < 3) {
            $excludeIds = array_merge($savedIds, $recommended->pluck('id')->toArray());
            $filler = School::whereNotIn('id', $excludeIds)
                ->orderByDesc('rating')
                ->take(3 - $recommended->count())
                ->get();
            $recommended = $recommended->concat($filler);
        }

        // ── Recently Viewed ──
        $recentIds = array_slice(session('recent_schools', []), 0, 4);
        $recentlyViewed = empty($recentIds)
            ? collect()
            : School::whereIn('id', $recentIds)
                ->orderByRaw(SchoolController::orderByIdsRaw($recentIds))
                ->get();

        // ── Articles ──
        $articlesRead = Article::orderByDesc('views')->take(3)->get();

        return view('dashboard', [
            'user' => $user,
            'stats' => $stats,
            'applications' => $applications,
            'savedSchools' => $savedSchools,
            'recommended' => $recommended,
            'recentlyViewed' => $recentlyViewed,
            'articlesRead' => $articlesRead,
        ]);
    }
}
