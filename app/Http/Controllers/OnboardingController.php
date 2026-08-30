<?php

namespace App\Http\Controllers;

use App\Models\Application;
use App\Services\ApplicationService;
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

        $application->loadMissing(['school', 'documents', 'payments', 'verifiedBy']);

        return view('onboarding.show', compact('application'));
    }

    /**
     * Show payment page for an application.
     */
    public function pay(Request $request, Application $application): View
    {
        $this->authorize('createPayment', $application);

        $application->loadMissing(['school', 'latestPayment']);

        return view('onboarding.pay', compact('application'));
    }
}
