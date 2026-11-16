<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Anonyme Besucherstatistik: ein Eintrag je Seitenaufruf, ohne IP-Adresse und ohne Cookies.
 * "visitor" ist ein täglich wechselnder, nicht umkehrbarer Hashwert.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('page_views', function (Blueprint $table) {
            $table->id();
            $table->date('viewed_on');
            $table->string('path', 255);
            $table->string('route', 80)->nullable();
            $table->string('locale', 2)->default('de');
            $table->string('referrer', 120)->nullable();
            $table->string('device', 10)->default('desktop');
            $table->char('visitor', 16);
            $table->timestamp('created_at')->nullable();

            $table->index(['viewed_on', 'visitor']);
            $table->index(['viewed_on', 'path']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('page_views');
    }
};
