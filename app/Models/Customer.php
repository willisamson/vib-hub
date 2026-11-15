<?php

namespace App\Models;

use App\Notifications\CustomerResetPassword;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

/**
 * Kundenkonto für die Website (eigener Login unter /konto).
 * Bewusst getrennt von den Admin-Benutzern (Tabelle users).
 */
class Customer extends Authenticatable
{
    use Notifiable;

    protected $fillable = [
        'name', 'company', 'email', 'phone', 'password', 'locale',
        'is_active', 'privacy_accepted_at', 'last_login_at',
    ];

    protected $hidden = ['password', 'remember_token'];

    protected function casts(): array
    {
        return [
            'password' => 'hashed',
            'is_active' => 'boolean',
            'privacy_accepted_at' => 'datetime',
            'last_login_at' => 'datetime',
        ];
    }

    public function inquiries(): HasMany
    {
        return $this->hasMany(Inquiry::class);
    }

    public function sendPasswordResetNotification($token): void
    {
        $this->notify(new CustomerResetPassword($token, $this->locale ?: app()->getLocale()));
    }
}
