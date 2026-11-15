<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        // Kundenkonto: nicht angemeldet → Anmeldung, bereits angemeldet → Mein Konto
        // (der Admin-Bereich hat eigene Weiterleitungen und ist davon nicht betroffen)
        $middleware->redirectGuestsTo(fn (Request $request) => route(
            $request->is('en', 'en/*') ? 'en.account.login' : 'account.login'
        ));
        $middleware->redirectUsersTo(fn (Request $request) => route(
            $request->is('en', 'en/*') ? 'en.account.dashboard' : 'account.dashboard'
        ));
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        $exceptions->shouldRenderJsonWhen(
            fn (Request $request) => $request->is('api/*') || $request->expectsJson(),
        );
    })->create();
