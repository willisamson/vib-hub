<?php

namespace App\Notifications;

use Filament\Auth\MultiFactor\Email\Notifications\VerifyEmailAuthentication;

/**
 * Anmeldecode per E-Mail – sofort versenden statt über die Warteschlange,
 * damit der Code auch ohne laufenden Queue-Worker ankommt.
 */
class TwoFactorCode extends VerifyEmailAuthentication
{
    public $connection = 'sync';
}
