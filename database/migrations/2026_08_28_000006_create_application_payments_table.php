<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('application_payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('application_id')->constrained()->cascadeOnDelete();
            $table->string('provider')->default('xendit'); // xendit | manual
            $table->string('external_id')->unique()->nullable();
            $table->string('idempotency_key')->unique()->nullable();
            $table->string('invoice_url')->nullable();
            $table->unsignedBigInteger('amount');
            $table->json('breakdown_json');
            $table->string('status')->default('pending'); // pending, paid, expired, failed
            $table->string('payment_method')->nullable();
            $table->timestamp('paid_at')->nullable();
            $table->json('raw_callback')->nullable();
            $table->foreignId('recorded_by_user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('application_payments');
    }
};
