<?php

namespace App\Http\Controllers;

use App\Http\Controllers\SchoolController as SchoolCtrl;
use App\Models\School;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CompareController extends Controller
{
    public function index(Request $request): View
    {
        $ids = $request->input('s');
        $ids = $ids ? array_map('intval', explode(',', $ids)) : [];
        $ids = array_slice(array_filter($ids), 0, 3);

        $schools = School::whereIn('id', $ids)
            ->with(['facilities', 'programs'])
            ->orderByRaw(SchoolCtrl::orderByIdsRaw($ids))
            ->get();

        $others = School::whereNotIn('id', $schools->pluck('id'))
            ->orderByDesc('rating')
            ->get();

        return view('compare.index', [
            'schools' => $schools,
            'others' => $others,
            'ids' => $schools->pluck('id')->implode(','),
        ]);
    }

    public function add(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'school_id' => 'required|exists:schools,id',
            'current' => 'nullable|string',
        ]);

        $current = array_filter(array_map('intval', explode(',', (string) $validated['current'])));
        $current[] = (int) $validated['school_id'];
        $current = array_slice(array_unique($current), 0, 3);

        return redirect()->route('compare.index', ['s' => implode(',', $current)]);
    }

    public function remove(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'school_id' => 'required|integer',
            'current' => 'nullable|string',
        ]);

        $current = array_filter(array_map('intval', explode(',', (string) $validated['current'])));
        $current = array_values(array_diff($current, [(int) $validated['school_id']]));

        return redirect()->route('compare.index', ['s' => implode(',', $current)]);
    }
}
