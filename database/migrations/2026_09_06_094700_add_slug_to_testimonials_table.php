<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('testimonials', function (Blueprint $table) {
            $table->string('slug')->unique()->nullable()->after('name');
            $table->boolean('is_published')->default(true)->after('rating');
        });

        // Backfill existing rows
        foreach (\App\Models\Testimonial::all() as $testimonial) {
            $testimonial->update([
                'slug' => Str::slug($testimonial->name . '-' . $testimonial->id),
            ]);
        }
    }

    public function down(): void
    {
        Schema::table('testimonials', function (Blueprint $table) {
            $table->dropColumn(['slug', 'is_published']);
        });
    }
};
