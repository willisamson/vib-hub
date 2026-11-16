<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;

/** Notfall: Zwei-Faktor-Anmeldung eines Admins zurücksetzen (z. B. Handy verloren, keine Wiederherstellungscodes). */
class ResetTwoFactor extends Command
{
    protected $signature = 'vibhub:2fa-reset {email : E-Mail-Adresse des Admins}';

    protected $description = 'Zwei-Faktor-Anmeldung eines Admins zurücksetzen; bei der nächsten Anmeldung wird sie neu eingerichtet';

    public function handle(): int
    {
        $user = User::query()->where('email', $this->argument('email'))->first();
        if (! $user) {
            $this->error('Kein Admin mit dieser E-Mail-Adresse.');

            return self::FAILURE;
        }

        $user->forceFill(['app_authentication_secret' => null, 'app_authentication_recovery_codes' => null])->save();
        $this->info("Zwei-Faktor-Anmeldung für {$user->email} zurückgesetzt. Bei der nächsten Anmeldung wird sie neu eingerichtet.");

        return self::SUCCESS;
    }
}
