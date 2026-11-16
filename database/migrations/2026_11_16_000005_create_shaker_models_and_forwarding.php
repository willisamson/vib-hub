<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/** Shaker-Auslegung (Modelle für die Beispielauslegung) und Weiterleitung von Anfragen an den Partner. */
return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('shaker_models')) {
            Schema::create('shaker_models', function (Blueprint $table) {
                $table->id();
                $table->string('manufacturer')->default('RMS');
                $table->string('name');
                $table->decimal('sine_force_kn', 8, 2);
                $table->decimal('random_force_kn', 8, 2);
                $table->decimal('shock_force_kn', 8, 2);
                $table->decimal('displacement_mm', 6, 1)->comment('Spitze-Spitze, Dauerbetrieb');
                $table->decimal('shock_displacement_mm', 6, 1)->nullable();
                $table->decimal('velocity_ms', 5, 2);
                $table->decimal('shock_velocity_ms', 5, 2)->nullable();
                $table->decimal('moving_mass_kg', 7, 1);
                $table->decimal('max_load_kg', 8, 1)->nullable();
                $table->unsignedInteger('f_min')->default(5);
                $table->unsignedInteger('f_max')->default(2500);
                $table->unsignedInteger('table_mm')->nullable();
                $table->string('cooling')->nullable();
                $table->boolean('is_active')->default(true);
                $table->unsignedInteger('sort')->default(0);
                $table->timestamps();
            });

            $now = now();
            foreach (require database_path('data/shaker-models.php') as $i => $m) {
                DB::table('shaker_models')->insert([
                    'manufacturer' => 'RMS', 'name' => $m['name'],
                    'sine_force_kn' => $m['sine'], 'random_force_kn' => $m['random'], 'shock_force_kn' => $m['shock'],
                    'displacement_mm' => $m['disp'], 'shock_displacement_mm' => $m['disp_shock'],
                    'velocity_ms' => $m['vel'], 'shock_velocity_ms' => $m['vel_shock'],
                    'moving_mass_kg' => $m['moving'], 'max_load_kg' => $m['load'],
                    'f_min' => $m['f_min'], 'f_max' => $m['f_max'], 'table_mm' => $m['table'], 'cooling' => $m['cooling'],
                    'is_active' => true, 'sort' => ($i + 1) * 10, 'created_at' => $now, 'updated_at' => $now,
                ]);
            }
        }

        Schema::table('inquiries', function (Blueprint $table) {
            if (! Schema::hasColumn('inquiries', 'forward_consent')) {
                $table->boolean('forward_consent')->default(false)->after('message');
                $table->string('forwarded_to')->nullable()->after('forward_consent');
                $table->timestamp('forwarded_at')->nullable()->after('forwarded_to');
            }
        });

        // Menüpunkt in der Fußzeile (nur wenn noch nicht vorhanden)
        if (Schema::hasTable('menu_items') && ! DB::table('menu_items')->where('route', 'planner')->exists()) {
            $sort = (int) DB::table('menu_items')->where('location', 'footer')->where('route', 'simulator')->value('sort');
            DB::table('menu_items')->where('location', 'footer')->where('sort', '>', $sort)->increment('sort');
            DB::table('menu_items')->insert([
                'location' => 'footer', 'label' => 'Shaker-Auslegung', 'label_en' => 'Shaker sizing', 'type' => 'route',
                'route' => 'planner', 'new_tab' => false, 'highlight' => false, 'is_active' => true, 'sort' => $sort + 1,
                'created_at' => now(), 'updated_at' => now(),
            ]);
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('shaker_models');
        Schema::table('inquiries', function (Blueprint $table) {
            $table->dropColumn(['forward_consent', 'forwarded_to', 'forwarded_at']);
        });
        if (Schema::hasTable('menu_items')) {
            DB::table('menu_items')->where('route', 'planner')->delete();
        }
    }
};
