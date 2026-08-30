<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('schools', function (Blueprint $table) {
            $table->string('whatsapp_e164')->nullable()->after('study_tour_fee');
            $table->string('whatsapp_label')->nullable()->after('whatsapp_e164');
            $table->decimal('latitude', 10, 7)->nullable()->after('whatsapp_label');
            $table->decimal('longitude', 10, 7)->nullable()->after('latitude');
            $table->boolean('is_published')->default(true)->after('longitude');
            $table->string('admission_status')->default('open')->after('is_published');
            $table->text('admission_notes')->nullable()->after('admission_status');
            $table->string('meta_title')->nullable()->after('admission_notes');
            $table->text('meta_description')->nullable()->after('meta_title');
        });
    }

    public function down(): void
    {
        Schema::table('schools', function (Blueprint $table) {
            $table->dropColumn([
                'whatsapp_e164', 'whatsapp_label', 'latitude', 'longitude',
                'is_published', 'admission_status', 'admission_notes',
                'meta_title', 'meta_description',
            ]);
        });
    }
};
