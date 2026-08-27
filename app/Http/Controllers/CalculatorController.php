<?php

namespace App\Http\Controllers;

use App\Models\School;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CalculatorController extends Controller
{
    private function parseRupiah(mixed $value): int
    {
        // Selalu buang pemisah ribuan (titik) pada input terformat "1.500.000"
        $digits = preg_replace('/[^0-9]/', '', (string) $value);

        return (int) ($digits ?: 0);
    }

    public function index(Request $request): View
    {
        $input = [
            'pangkal' => $this->parseRupiah($request->input('pangkal', 6000000)),
            'spp' => $this->parseRupiah($request->input('spp', 1500000)),
            'asrama' => $this->parseRupiah($request->input('asrama', 500000)),
            'seragam' => $this->parseRupiah($request->input('seragam', 1000000)),
            'ekskul' => $this->parseRupiah($request->input('ekskul', 750000)),
            'tour' => $this->parseRupiah($request->input('tour', 1000000)),
            'durasi' => max(1, (int) $request->input('durasi', 12)), // bulan
        ];

        $monthly = $input['spp'] + $input['asrama'];
        $yearlyRecurring = $monthly * $input['durasi'];
        $oneTime = $input['pangkal'] + $input['seragam'] + $input['ekskul'] + $input['tour'];
        $total = $oneTime + $yearlyRecurring;

        $schools = School::orderBy('name')->get(['id', 'name', 'uang_pangkal', 'spp_monthly', 'asrama_monthly', 'seragam_fee', 'ekskul_fee', 'study_tour_fee']);

        return view('calculator.index', [
            'input' => $input,
            'result' => [
                'monthly' => $monthly,
                'oneTime' => $oneTime,
                'yearlyRecurring' => $yearlyRecurring,
                'total' => $total,
                'avgMonthly' => (int) round($total / max($input['durasi'], 1)),
            ],
            'schools' => $schools,
        ]);
    }
}
