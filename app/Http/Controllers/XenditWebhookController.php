<?php

namespace App\Http\Controllers;

use App\Services\XenditService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class XenditWebhookController extends Controller
{
    public function __construct(
        private XenditService $xenditService,
    ) {}

    /**
     * Handle Xendit webhook callback.
     */
    public function handle(Request $request): JsonResponse
    {
        $this->xenditService->handleWebhook($request);

        return response()->json(['status' => 'ok']);
    }
}
