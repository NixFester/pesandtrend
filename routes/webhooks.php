<?php

use App\Http\Controllers\XenditWebhookController;
use Illuminate\Support\Facades\Route;

Route::post('/xendit', [XenditWebhookController::class, 'handle'])->name('webhook.xendit');
