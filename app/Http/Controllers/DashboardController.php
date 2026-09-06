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
                ->orderByRaw(SchoolController::orderByIdsRaw($recentIds))
                ->get();

        $articlesRead = Article::orderByDesc('views')->take(3)->get();

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

        return view('dashboard', [
            'user' => $user,
            'applications' => $applications,
            'savedSchools' => $savedSchools,
            'recommended' => $recommended,
            'recentlyViewed' => $recentlyViewed,
            'articlesRead' => $articlesRead,
        ]);
    }
}
