<?php

namespace App\Http\Controllers;

use App\Models\ApplicationPayment;
use App\Services\PdfRenderer;
use Illuminate\Http\Response;

class ProofController extends Controller
{
    public function __construct(
        private PdfRenderer $pdfRenderer,
    ) {}

    /**
     * Download proof of payment PDF (requires signed URL).
     */
    public function print(ApplicationPayment $payment): Response
    {
        if (! $payment->isPaid()) {
            abort(404, 'Bukti pembayaran belum tersedia.');
        }

        $binary = $this->pdfRenderer->proof($payment);

        $filename = "bukti-pembayaran-{$payment->application->public_id}.pdf";

        return response($binary, 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ]);
    }
}
