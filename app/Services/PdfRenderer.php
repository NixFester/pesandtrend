<?php

namespace App\Services;

use App\Models\ApplicationPayment;
use Barryvdh\DomPDF\Facade\Pdf;

class PdfRenderer
{
    /**
     * Render a proof of payment PDF.
     */
    public function proof(ApplicationPayment $payment): string
    {
        $payment->loadMissing(['application.school']);

        $pdf = Pdf::loadView('payments.proof', [
            'payment' => $payment,
            'application' => $payment->application,
            'school' => $payment->application->school,
        ])->setPaper('a4');

        return $pdf->output();
    }
}
