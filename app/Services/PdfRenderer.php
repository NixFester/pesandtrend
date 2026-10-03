<?php

namespace App\Services;

use App\Models\ApplicationPayment;
use App\Models\MentorBooking;
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

    /**
     * Render a bimbel mentor booking proof of payment PDF.
     */
    public function mentorBookingProof(MentorBooking $booking): string
    {
        $booking->loadMissing('mentor');

        $pdf = Pdf::loadView('bimbel.proof', [
            'booking' => $booking,
            'mentor' => $booking->mentor,
        ])->setPaper('a4');

        return $pdf->output();
    }
}
