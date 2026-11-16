<?php

use App\Support\NormLibrary;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasColumn('norms', 'requirements')) {
            Schema::table('norms', function (Blueprint $table) {
                $table->json('requirements')->nullable()->after('parameters');
            });
        }

        // Anforderungen zu den vorhandenen Normen ergänzen und neue Normen anlegen.
        NormLibrary::sync();
    }

    public function down(): void
    {
        Schema::table('norms', function (Blueprint $table) {
            $table->dropColumn('requirements');
        });
    }
};
