<?php

namespace App\Http\Controllers;

use App\Models\School;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SchoolController extends Controller
{
    public function index(Request $request): View
    {
        $cities = School::orderBy('city')->pluck('city', 'city')->unique();
        $jenjang = ['SDIT' => 'SDIT', 'SMPIT' => 'SMPIT', 'SMA Islam' => 'SMA Islam', 'MA' => 'MA'];
        $types = [
            'pesantren' => 'Pesantren',
            'terpadu' => 'Sekolah Islam Terpadu',
            'berasrama' => 'Berasrama',
        ];

        $schools = School::query()
            ->filter($request->only(['q', 'kota', 'jenjang', 'tipe']))
            ->sorted($request->input('urut'))
            ->with(['facilities', 'programs'])
            ->paginate(9)
            ->withQueryString();

        $recentlyViewed = $this->recentlyViewed(4);

        return view('schools.index', [
            'schools' => $schools,
            'cities' => $cities,
            'jenjang' => $jenjang,
            'types' => $types,
            'filters' => $request->only(['q', 'kota', 'jenjang', 'tipe', 'urut']),
            'popularSearches' => ['Berasrama Bogor', 'SDIT Jakarta', 'SMPIT Unggulan', 'SMA Islam'],
            'recentlyViewed' => $recentlyViewed,
        ]);
    }

    public function show(string $slug)
    {
        $school = School::where('slug', $slug)
            ->with(['facilities', 'programs', 'achievements'])
            ->firstOrFail();

        // Catat sekolah yang terakhir dilihat (maksimal 8)
        $recent = session('recent_schools', []);
        array_unshift($recent, $school->id);
        $recent = array_values(array_unique($recent));
        $recent = array_slice($recent, 0, 8);
        session(['recent_schools' => $recent]);

        $related = School::where('id', '!=', $school->id)
            ->where(function ($q) use ($school) {
                $q->where('city', $school->city)
                    ->orWhere('type', $school->type);
            })
            ->orderByDesc('rating')
            ->take(3)
            ->get();

        $isSaved = auth()->check()
            && $school->savedByUsers()->where('user_id', auth()->id())->exists();

        return view('schools.show', [
            'school' => $school,
            'related' => $related,
            'isSaved' => $isSaved,
            'recentlyViewed' => $this->recentlyViewed(4, $school->id),
        ]);
    }

    public static function orderByIdsRaw(array $ids): string
    {
        if (empty($ids)) {
            return 'id';
        }

        $cases = collect($ids)
            ->map(fn ($id, $i) => "WHEN {$id} THEN {$i}")
            ->implode(' ');

        return "CASE id {$cases} ELSE ".count($ids).' END';
    }

    private function recentlyViewed(int $limit = 4, ?int $exceptId = null)
    {
        $ids = array_slice(session('recent_schools', []), 0, 8);

        if (empty($ids)) {
            return collect();
        }

        if ($exceptId) {
            $ids = array_values(array_diff($ids, [$exceptId]));
        }

        if (empty($ids)) {
            return collect();
        }

        return School::whereIn('id', $ids)
            ->orderByRaw(self::orderByIdsRaw($ids))
            ->take($limit)
            ->get();
    }
}
