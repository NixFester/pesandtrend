<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('applications', function (Blueprint $table) {
            $table->id();
            $table->ulid('public_id')->unique();
            $table->foreignId('school_id')->constrained()->cascadeOnDelete();
            $table->foreignId('created_by_user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('parent_user_id')->nullable()->constrained('users')->nullOnDelete();

            // Parent biodata
            $table->string('parent_name');
            $table->string('parent_email')->nullable();
            $table->string('parent_phone')->nullable();
            $table->string('parent_whatsapp')->nullable();

            // Student biodata
            $table->string('student_name');
            $table->text('student_nik_encrypted')->nullable();
            $table->string('student_nik_hash')->nullable()->index();
            $table->string('student_gender')->nullable();
            $table->string('student_birth_place')->nullable();
            $table->date('student_birth_date')->nullable();
            $table->text('student_address')->nullable();
            $table->string('previous_school')->nullable();
            $table->string('target_jenjang')->nullable();
            $table->text('notes')->nullable();

            // Status workflow
            $table->string('status')->default('draft')->index();
            $table->timestamp('submitted_at')->nullable();
            $table->foreignId('verified_by_user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('verified_at')->nullable();
            $table->text('rejection_reason')->nullable();

            $table->timestamps();

            $table->index(['school_id', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('applications');
    }
};
