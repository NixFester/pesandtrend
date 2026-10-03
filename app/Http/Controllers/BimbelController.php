<?php

namespace App\Http\Controllers;

use App\Models\Mentor;
use App\Models\MentorBooking;
use App\Services\BimbelService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\URL;
use Illuminate\View\View;

class BimbelController extends Controller
{
    public function __construct(
        protected BimbelService $bimbelService,
    ) {}

    /**
     * Display mentor catalog page.
     */
    public function index(): View
    {
        $mentors = Mentor::with('images')
            ->active()
            ->ordered()
            ->paginate(9);

        // Stats
        $totalMentors = Mentor::active()->count();
        $totalBookings = MentorBooking::paid()->count();
        $totalAmount = MentorBooking::paid()->sum('amount');
        $stats = [
            'total_mentors' => $totalMentors,
            'total_bookings' => $totalBookings,
            'total_amount' => $totalAmount,
            'total_formatted' => 'Rp'.number_format($totalAmount / 1000000, 1).'jt+',
        ];

        return view('bimbel.index', [
            'mentors' => $mentors,
            'stats' => $stats,
        ]);
    }

    /**
     * Display mentor detail page.
     */
    public function show(string $slug): View
    {
        $mentor = Mentor::with('images')
            ->where('slug', $slug)
            ->active()
            ->firstOrFail();

        $whatsappMessage = "Halo {$mentor->name}, saya ingin bertanya tentang layanan bimbel di Pesantrends.";
        $whatsappLink = $mentor->whatsappLink($whatsappMessage);

        return view('bimbel.show', [
            'mentor' => $mentor,
            'whatsappLink' => $whatsappLink,
        ]);
    }

    /**
     * Process booking - create booking record and Xendit invoice.
     */
    public function book(Request $request, string $slug): RedirectResponse
    {
        $mentor = Mentor::where('slug', $slug)->active()->firstOrFail();

        $validator = Validator::make($request->all(), [
            'client_name' => 'required|string|max:100',
            'client_email' => 'required|email|max:255',
            'client_whatsapp' => 'required|string|max:30',
        ]);

        if ($validator->fails()) {
            return redirect()
                ->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            $result = $this->bimbelService->createBooking($mentor, $request->only([
                'client_name',
                'client_email',
                'client_whatsapp',
            ]));

            // Redirect to payment page
            return redirect()->route('bimbel.payment', $result['booking']);
        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->withErrors(['error' => $e->getMessage()])
                ->withInput();
        }
    }

    /**
     * Show payment page with Xendit link.
     */
    public function payment(MentorBooking $booking): View
    {
        return view('bimbel.payment', [
            'booking' => $booking->load('mentor'),
        ]);
    }

    /**
     * Redirect to Xendit payment page.
     */
    public function processPayment(MentorBooking $booking): RedirectResponse
    {
        $booking->refresh();

        // Use stored invoice_url if available
        if ($booking->invoice_url) {
            return redirect()->away($booking->invoice_url);
        }

        // Fallback to Xendit checkout
        if ($booking->xendit_id) {
            return redirect()->away("https://checkout-staging.xendit.co/web/{$booking->xendit_id}");
        }

        return redirect()->back()->withErrors(['error' => 'Invoice Xendit tidak ditemukan']);
    }

    /**
     * Simulate successful payment (development only).
     */
    public function simulatePayment(MentorBooking $booking): RedirectResponse
    {
        $booking->update([
            'status' => 'paid',
            'payment_method' => 'XENDIT_SIMULATED',
            'paid_at' => now(),
        ]);

        return redirect()->route('bimbel.success', [
            'slug' => $booking->mentor->slug,
            'booking' => $booking,
        ]);
    }

    /**
     * Show success page.
     */
    public function success(string $slug, MentorBooking $booking): View
    {
        // If still pending, try to process webhook callback manually
        if ($booking->status === 'pending' && $booking->xendit_id) {
            $this->bimbelService->handleCallback([
                'external_id' => 'mentor_booking_'.$booking->id,
                'id' => $booking->xendit_id,
                'status' => 'PAID',
            ]);
            $booking->refresh();
        }

        $booking->load('mentor');

        $proofUrl = URL::signedRoute('bimbel.proof', ['booking' => $booking]);

        $whatsappMessage = "Halo {$booking->mentor->name}, saya {$booking->client_name} sudah melakukan pembayaran bimbel via Pesantrends (Kode: {$booking->code}). Bukti: {$proofUrl}";
        $whatsappLink = $booking->mentor->whatsappLink($whatsappMessage);

        return view('bimbel.success', [
            'booking' => $booking,
            'proofUrl' => $proofUrl,
            'whatsappLink' => $whatsappLink,
        ]);
    }

    /**
     * Show failed page.
     */
    public function failed(string $slug, MentorBooking $booking): View
    {
        return view('bimbel.failed', [
            'booking' => $booking->load('mentor'),
        ]);
    }
}
