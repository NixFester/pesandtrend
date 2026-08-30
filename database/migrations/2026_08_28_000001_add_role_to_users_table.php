<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('role')->default('parent')->index()->after('email');
            $table->string('phone')->nullable()->after('role');
            $table->string('whatsapp')->nullable()->after('phone');
            $table->text('address')->nullable()->after('whatsapp');
            $table->decimal('lat', 10, 7)->nullable()->after('address');
            $table->decimal('lng', 10, 7)->nullable()->after('lat');
            $table->timestamp('geocoded_at')->nullable()->after('lng');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['role', 'phone', 'whatsapp', 'address', 'lat', 'lng', 'geocoded_at']);
        });
    }
};
