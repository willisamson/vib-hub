<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/** Ein anonymer Seitenaufruf für die Besucherstatistik. */
class PageView extends Model
{
    public const UPDATED_AT = null;

    protected $fillable = ['viewed_on', 'path', 'route', 'locale', 'referrer', 'device', 'visitor'];

    protected function casts(): array
    {
        return ['viewed_on' => 'date'];
    }
}
