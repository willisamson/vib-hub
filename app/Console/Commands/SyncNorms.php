<?php

namespace App\Console\Commands;

use App\Support\NormLibrary;
use Illuminate\Console\Command;

class SyncNorms extends Command
{
    protected $signature = 'vibhub:normen {--force : Anforderungen und Eckdaten aus der Bibliothek überschreiben}';

    protected $description = 'Normen-Bibliothek einspielen (fehlende Normen und Anforderungen ergänzen)';

    public function handle(): int
    {
        if ($this->option('force') && ! $this->confirm('Im Admin geänderte Anforderungen und Eckdaten werden überschrieben. Fortfahren?', true)) {
            return self::FAILURE;
        }

        $result = NormLibrary::sync((bool) $this->option('force'));
        $this->info("Normen neu angelegt: {$result['created']}, ergänzt: {$result['updated']}");

        return self::SUCCESS;
    }
}
