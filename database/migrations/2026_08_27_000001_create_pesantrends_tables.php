<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('schools', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->string('type'); // Pesantren Modern, Pesantren Salafi, Sekolah Islam Terpadu, Full Day
            $table->json('jenjang'); // ["SDIT","SMPIT"]
            $table->string('city');
            $table->string('province');
            $table->text('address');
            $table->string('short_desc');
            $table->longText('description');
            $table->decimal('rating', 2, 1)->default(0);
            $table->unsignedInteger('reviews_count')->default(0);
            $table->unsignedInteger('students_count')->default(0);
            $table->string('teacher_ratio')->nullable();
            $table->unsignedSmallInteger('founded_year');
            $table->boolean('is_boarding')->default(false);
            $table->boolean('registration_open')->default(true);
            $table->boolean('is_verified')->default(true);
            $table->boolean('is_featured')->default(false);
            $table->string('accreditation', 10)->default('A');
            $table->string('image');
            $table->string('badge')->nullable(); // e.g. "Pendaftaran Buka"
            $table->json('tags');
            $table->json('alumni_stats')->nullable();
            // Biaya (IDR)
            $table->unsignedBigInteger('uang_pangkal')->default(0);
            $table->unsignedBigInteger('spp_monthly')->default(0);
            $table->unsignedBigInteger('asrama_monthly')->default(0);
            $table->unsignedBigInteger('seragam_fee')->default(0);
            $table->unsignedBigInteger('ekskul_fee')->default(0);
            $table->unsignedBigInteger('study_tour_fee')->default(0);
            $table->timestamps();
        });

        Schema::create('facilities', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->string('icon')->default('check');
            $table->timestamps();
        });

        Schema::create('school_facility', function (Blueprint $table) {
            $table->id();
            $table->foreignId('school_id')->constrained()->cascadeOnDelete();
            $table->foreignId('facility_id')->constrained()->cascadeOnDelete();
        });

        Schema::create('programs', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->string('icon')->default('star');
            $table->timestamps();
        });

        Schema::create('school_program', function (Blueprint $table) {
            $table->id();
            $table->foreignId('school_id')->constrained()->cascadeOnDelete();
            $table->foreignId('program_id')->constrained()->cascadeOnDelete();
        });

        Schema::create('achievements', function (Blueprint $table) {
            $table->id();
            $table->foreignId('school_id')->constrained()->cascadeOnDelete();
            $table->string('title');
            $table->unsignedSmallInteger('year')->nullable();
            $table->timestamps();
        });

        Schema::create('articles', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('slug')->unique();
            $table->string('category');
            $table->string('excerpt');
            $table->longText('content');
            $table->string('image');
            $table->unsignedSmallInteger('read_minutes')->default(5);
            $table->unsignedBigInteger('views')->default(0);
            $table->timestamp('published_at')->nullable();
            $table->timestamps();
        });

        Schema::create('testimonials', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('role')->nullable();
            $table->text('quote');
            $table->unsignedTinyInteger('rating')->default(5);
            $table->timestamps();
        });

        Schema::create('subscribers', function (Blueprint $table) {
            $table->id();
            $table->string('email')->unique();
            $table->timestamps();
        });

        Schema::create('saved_schools', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('school_id')->constrained()->cascadeOnDelete();
            $table->timestamps();
            $table->unique(['user_id', 'school_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('saved_schools');
        Schema::dropIfExists('subscribers');
        Schema::dropIfExists('testimonials');
        Schema::dropIfExists('articles');
        Schema::dropIfExists('achievements');
        Schema::dropIfExists('school_program');
        Schema::dropIfExists('programs');
        Schema::dropIfExists('school_facility');
        Schema::dropIfExists('facilities');
        Schema::dropIfExists('schools');
    }
};
