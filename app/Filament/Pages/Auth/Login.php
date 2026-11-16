<?php

namespace App\Filament\Pages\Auth;

use Filament\Auth\Pages\Login as BaseLogin;
use Illuminate\Contracts\Support\Htmlable;

/** Admin-Anmeldung mit Logo (aus Website & Logo). */
class Login extends BaseLogin
{
    public function getHeading(): string|Htmlable|null
    {
        return filled($this->userUndertakingMultiFactorAuthentication) ? parent::getHeading() : 'vib-hub Anmeldung';
    }
}
