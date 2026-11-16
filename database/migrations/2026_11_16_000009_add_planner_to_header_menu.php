<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/** Shaker-Auslegung ins Hauptmenü (hinter „Berechnungen“), falls noch nicht vorhanden. */
return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('menu_items') || DB::table('menu_items')->where('location', 'header')->where('route', 'planner')->exists()) {
            return;
        }

        $after = DB::table('menu_items')->where('location', 'header')->where('route', 'calculators')->value('sort');
        $sort = $after === null ? (int) DB::table('menu_items')->where('location', 'header')->max('sort') + 1 : (int) $after + 1;

        DB::table('menu_items')->where('location', 'header')->where('sort', '>=', $sort)->increment('sort');
        DB::table('menu_items')->insert([
            'location' => 'header', 'label' => 'Auslegung', 'label_en' => 'Sizing', 'type' => 'route',
            'route' => 'planner', 'new_tab' => false, 'highlight' => false, 'is_active' => true, 'sort' => $sort,
            'created_at' => now(), 'updated_at' => now(),
        ]);
    }

    public function down(): void
    {
        DB::table('menu_items')->where('location', 'header')->where('route', 'planner')->delete();
    }
};
