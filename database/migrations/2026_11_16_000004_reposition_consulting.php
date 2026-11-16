<?php

use App\Support\ContentPatch;
use Illuminate\Database\Migrations\Migration;

/** Neuausrichtung auf Beratung und Prüfstandsaufbau (kein eigener Shaker). */
return new class extends Migration
{
    public function up(): void
    {
        ContentPatch::apply('content-beratung');
    }

    public function down(): void
    {
        // Inhalte lassen sich im Admin anpassen; kein automatisches Zurücksetzen.
    }
};
