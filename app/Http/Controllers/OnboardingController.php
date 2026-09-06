<?php

namespace App\Http\Controllers;

use App\Domain\Onboarding\ApplicationStatus;
use App\Models\Application;
use App\Services\ApplicationService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class OnboardingController extends Controller
{
    public function __construct(
        private ApplicationService $applicationService,
    ) {}

    /**
     * List parent's own applications.
     */
    public function index(Request $request): View
    {
        $applications = Application::where('parent_user_id', $request->user()->id)
            ->with(['school', 'latestPayment'])
            ->latest()
            ->paginate(10);

        return view('onboarding.index', compact('applications'));
    }

    /**
     * Show a single application detail.
     */
    public function show(Request $request, Application $application): View
    {
        $this->authorize('view', $application);

        // Auto-complete simulated payment callback in dev / testing mode
        if (in_array($request->query('payment'), ['simulated', 'success', 'paid'], true)) {
            $latestPayment = $application->latestPayment;
            if ($latestPayment && ! $latestPayment->isPaid()) {
                $latestPayment->update([
                    'status' => 'paid',
                    'payment_method' => 'XENDIT_SIMULATED',
                    'paid_at' => now(),
                ]);

                if (in_array($application->status, [ApplicationStatus::PaymentPending, ApplicationStatus::Submitted], true)) {
                    $application->update(['status' => ApplicationStatus::Paid]);
                }

                session()->flash('payment_success', 'Pembayaran berhasil dikonfirmasi!');
            }
        }

        $application->loadMissing(['school', 'documents', 'payments', 'verifiedBy']);

        return view('onboarding.show', compact('application'));
    }

    /**
     * Show payment page for an application.
     */
    public function pay(Request $request, Application $application): View|RedirectResponse
    {
        $this->authorize('createPayment', $application);

        if ($request->has('simulate')) {
            $latestPayment = $application->latestPayment;
            if ($latestPayment) {
                $latestPayment->update([
                    'status' => 'paid',
                    'payment_method' => 'XENDIT_SIMULATED',
                    'paid_at' => now(),
                ]);
            }

            $application->update(['status' => ApplicationStatus::Paid]);

            session()->flash('payment_success', 'Simulasi pembayaran Xendit berhasil!');

            return redirect()->route('onboarding.show', $application);
        }

        $application->loadMissing(['school', 'latestPayment']);

        return view('onboarding.pay', compact('application'));
    }
}
