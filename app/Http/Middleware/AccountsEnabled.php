<?php

namespace App\Http\Middleware;

use App\Support\Site;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/** Kundenkonten lassen sich im Admin abschalten – dann gibt es /konto nicht. */
class AccountsEnabled
{
    public function __construct(protected Site $site) {}

    public function handle(Request $request, Closure $next): Response
    {
        abort_unless($this->site->accountsEnabled(), 404);

        return $next($request);
    }
}
