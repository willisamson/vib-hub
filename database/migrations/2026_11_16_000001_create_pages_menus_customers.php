<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * Eigene Seiten, pflegbare Menüs (Kopf- und Fußzeile) und Kundenkonten.
 * Die Menüs werden mit den bisherigen Einträgen vorbelegt.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pages', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('slug')->unique();
            $table->text('excerpt')->nullable();
            $table->longText('body')->nullable();
            $table->json('en')->nullable();
            $table->boolean('is_published')->default(true);
            $table->timestamps();
        });

        Schema::create('menu_items', function (Blueprint $table) {
            $table->id();
            $table->string('location', 20)->default('header')->index();
            $table->string('label');
            $table->string('label_en')->nullable();
            $table->string('type', 10)->default('route');
            $table->string('route', 60)->nullable();
            $table->foreignId('page_id')->nullable()->constrained('pages')->nullOnDelete();
            $table->string('url')->nullable();
            $table->boolean('new_tab')->default(false);
            $table->boolean('highlight')->default(false);
            $table->boolean('is_active')->default(true);
            $table->unsignedInteger('sort')->default(0);
            $table->timestamps();
        });

        Schema::create('customers', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('company')->nullable();
            $table->string('email')->unique();
            $table->string('phone', 60)->nullable();
            $table->string('password');
            $table->string('locale', 2)->default('de');
            $table->boolean('is_active')->default(true);
            $table->timestamp('privacy_accepted_at')->nullable();
            $table->timestamp('last_login_at')->nullable();
            $table->rememberToken();
            $table->timestamps();
        });

        Schema::create('customer_password_reset_tokens', function (Blueprint $table) {
            $table->string('email')->primary();
            $table->string('token');
            $table->timestamp('created_at')->nullable();
        });

        Schema::table('inquiries', function (Blueprint $table) {
            $table->foreignId('customer_id')->nullable()->after('id')->constrained('customers')->nullOnDelete();
            $table->text('reply')->nullable()->after('notes');
            $table->timestamp('replied_at')->nullable()->after('reply');
        });

        $now = now();
        $items = [
            ['header', 'Leistungen', 'Services', 'services.index', false],
            ['header', 'Berechnungen', 'Calculations', 'calculators', false],
            ['header', 'Simulator', 'Simulator', 'simulator', false],
            ['header', 'App', 'App', 'app', true],
            ['header', 'Normen', 'Standards', 'norms.index', false],
            ['header', 'Schulungen', 'Training', 'trainings.index', false],
            ['footer', 'Alle Leistungen', 'All services', 'services.index', false],
            ['footer', 'Berechnungen', 'Calculations', 'calculators', false],
            ['footer', 'Regler-Simulator', 'Controller simulator', 'simulator', false],
            ['footer', 'Normen', 'Standards', 'norms.index', false],
            ['footer', 'Schulungen', 'Training', 'trainings.index', false],
            ['footer', 'App', 'App', 'app', false],
            ['legal', 'Impressum', 'Legal notice', 'imprint', false],
            ['legal', 'Datenschutz', 'Privacy', 'privacy', false],
        ];

        DB::table('menu_items')->insert(array_map(fn (array $item, int $i) => [
            'location' => $item[0],
            'label' => $item[1],
            'label_en' => $item[2],
            'type' => 'route',
            'route' => $item[3],
            'highlight' => $item[4],
            'is_active' => true,
            'new_tab' => false,
            'sort' => $i,
            'created_at' => $now,
            'updated_at' => $now,
        ], $items, array_keys($items)));
    }

    public function down(): void
    {
        Schema::table('inquiries', function (Blueprint $table) {
            $table->dropConstrainedForeignId('customer_id');
            $table->dropColumn(['reply', 'replied_at']);
        });
        Schema::dropIfExists('customer_password_reset_tokens');
        Schema::dropIfExists('customers');
        Schema::dropIfExists('menu_items');
        Schema::dropIfExists('pages');
    }
};
