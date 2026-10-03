<?php

namespace App\Http\Controllers;

use App\Models\Campaign;
use App\Models\Donation;
use App\Services\DonationService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\View\View;

class DonationController extends Controller
{
    public function __construct(
        protected DonationService $donationService,
    ) {}

    /**
     * Display campaign listing page.
     */
    public function index(Request $request): View
    {
        $query = Campaign::with('school')
            ->active()
            ->orderByDesc('is_featured')
            ->orderByDesc('created_at');

        // Filter by category
        if ($request->filled('kategori')) {
            $query->byCategory($request->kategori);
        }

        $campaigns = $query->paginate(9)->withQueryString();

        $featuredCampaigns = Campaign::with('school')
            ->active()
            ->featured()
            ->limit(3)
            ->get();

        $categories = Campaign::categories();

        // Stats
        $totalDonations = Donation::paid()->count();
        $totalAmount = Donation::paid()->sum('amount');
        $stats = [
            'total_campaigns' => Campaign::active()->count(),
            'total_donors' => $totalDonations,
            'total_amount' => $totalAmount,
            'total_formatted' => 'Rp'.number_format($totalAmount / 1000000, 1).'jt+',
        ];

        return view('donations.index', [
            'campaigns' => $campaigns,
            'featuredCampaigns' => $featuredCampaigns,
            'categories' => $categories,
            'stats' => $stats,
        ]);
    }

    /**
     * Display campaign detail page.
     */
    public function show(string $slug): View
    {
        $campaign = Campaign::with([
            'school',
            'donations' => fn ($query) => $query->paid(),
        ])
            ->where('slug', $slug)
            ->active()
            ->firstOrFail();

        $recentDonations = $campaign->donations()
            ->paid()
            ->orderByDesc('paid_at')
            ->limit(10)
            ->get();

        return view('donations.show', [
            'campaign' => $campaign,
            'recentDonations' => $recentDonations,
            'presetAmounts' => $this->donationService->presetAmounts(),
        ]);
    }

    /**
     * Process donation - create donation record and Xendit invoice.
     */
    public function donate(Request $request, string $slug): RedirectResponse
    {
        $campaign = Campaign::where('slug', $slug)->active()->firstOrFail();

        $validator = Validator::make($request->all(), [
            'donor_name' => 'required|string|max:100',
            'donor_email' => 'required|email|max:255',
            'donor_phone' => 'nullable|string|max:20',
            'donor_message' => 'nullable|string|max:500',
            'amount' => 'required|integer|min:10000|max:100000000',
        ]);

        if ($validator->fails()) {
            return redirect()
                ->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            $result = $this->donationService->createDonation([
                'campaign_id' => $campaign->id,
                ...$request->only([
                    'donor_name',
                    'donor_email',
                    'donor_phone',
                    'donor_message',
                    'amount',
                ]),
            ]);

            // Redirect to payment page
            return redirect()->route('donations.payment', $result['donation']);
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
    public function payment(Donation $donation): View
    {
        $campaign = $donation->campaign;

        return view('donations.payment', [
            'donation' => $donation,
            'campaign' => $campaign,
        ]);
    }

    /**
     * Redirect to Xendit payment page.
     */
    public function processPayment(Donation $donation): RedirectResponse
    {
        $donation->refresh();

        // Use stored invoice_url if available
        if ($donation->invoice_url) {
            return redirect()->away($donation->invoice_url);
        }

        // Fallback to Xendit checkout
        if ($donation->xendit_id) {
            return redirect()->away("https://checkout-staging.xendit.co/web/{$donation->xendit_id}");
        }

        return redirect()->back()->withErrors(['error' => 'Invoice Xendit tidak ditemukan']);
    }

    /**
     * Simulate successful payment (development only).
     */
    public function simulatePayment(Donation $donation): RedirectResponse
    {
        $donation->update([
            'status' => 'paid',
            'payment_method' => 'XENDIT_SIMULATED',
            'paid_at' => now(),
        ]);

        $donation->campaign->increment('current_amount', $donation->amount);

        return redirect()->route('donations.success', $donation);
    }

    /**
     * Handle Xendit callback.
     */
    public function xenditCallback(Request $request): RedirectResponse
    {
        $payload = $request->all();

        // Handle asynchronously in production
        // For now, handle synchronously
        $this->donationService->handleXenditCallback($payload);

        return redirect()->route('donations.index');
    }

    /**
     * Show success page.
     */
    public function success(Donation $donation): View
    {
        // If still pending, try to process webhook callback manually
        if ($donation->status === 'pending' && $donation->xendit_id) {
            $this->donationService->handleXenditCallback([
                'id' => $donation->xendit_id,
                'status' => 'PAID',
            ]);
            $donation->refresh();
        }

        return view('donations.success', [
            'donation' => $donation->load('campaign'),
        ]);
    }

    /**
     * Show failed page.
     */
    public function failed(Donation $donation): View
    {
        return view('donations.failed', [
            'donation' => $donation->load('campaign'),
        ]);
    }
}
