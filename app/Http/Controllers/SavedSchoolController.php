<?php

namespace App\Http\Controllers;

use App\Models\School;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class SavedSchoolController extends Controller
{
    public function toggle(Request $request, School $school): RedirectResponse
    {
        $user = $request->user();
        $saved = $user->savedSchools()->where('school_id', $school->id)->exists();

        if ($saved) {
            $user->savedSchools()->detach($school->id);
            $message = 'Sekolah dihapus dari daftar tersimpan.';
        } else {
            $user->savedSchools()->attach($school->id);
            $message = 'Sekolah berhasil disimpan!';
        }

        return back()->with('save_success', $message);
    }
}
