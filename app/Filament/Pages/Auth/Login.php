<?php

namespace App\Filament\Pages\Auth;

use Filament\Auth\Pages\Login as BaseLogin;
use Illuminate\Contracts\Support\Htmlable;

/** Admin-Anmeldung mit Markenbereich (Logo aus Website & Logo). */
class Login extends BaseLogin
{
    public function hasLogo(): bool
    {
        return false; // Das Logo steht groß im Markenbereich daneben
    }

    public function getHeading(): string|Htmlable|null
    {
        return filled($this->userUndertakingMultiFactorAuthentication) ? parent::getHeading() : 'Admin-Anmeldung';
    }

    public function getSubheading(): string|Htmlable|null
    {
        return filled($this->userUndertakingMultiFactorAuthentication) ? parent::getSubheading() : 'Melden Sie sich mit Ihrem Admin-Zugang an.';
    }
}
