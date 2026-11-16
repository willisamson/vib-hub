<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Besucherstatistik: Land, Browser und Betriebssystem.
 * Das Land wird beim Aufruf aus der IP-Adresse nachgeschlagen; die IP selbst wird nicht gespeichert.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('page_views', function (Blueprint $table) {
            if (! Schema::hasColumn('page_views', 'country')) {
                $table->char('country', 2)->nullable()->after('device');
                $table->string('browser', 30)->nullable()->after('country');
                $table->string('os', 30)->nullable()->after('browser');
            }
        });

        if (! Schema::hasTable('geoip_ranges')) {
            Schema::create('geoip_ranges', function (Blueprint $table) {
                $table->id();
                $table->char('ip_start', 32)->index();   // Hex, siehe App\Support\GeoIp::key()
                $table->char('ip_end', 32);
                $table->char('country', 2);
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('geoip_ranges');
        Schema::table('page_views', function (Blueprint $table) {
            $table->dropColumn(['country', 'browser', 'os']);
        });
    }
};
