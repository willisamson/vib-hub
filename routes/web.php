<?php

use App\Http\Controllers\Account\AccountController;
use App\Http\Controllers\Account\AuthController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\SiteController;
use App\Http\Middleware\AccountsEnabled;
use App\Http\Middleware\SetLocale;
use Illuminate\Support\Facades\Route;

// Alle Seiten gibt es auf Deutsch (/) und Englisch (/en/...).
// Die Pfade bleiben in beiden Sprachen gleich, nur das Präfix /en kommt dazu.
$pages = function (): void {
    Route::get('/', [SiteController::class, 'home'])->name('home');

    Route::get('/leistungen', [SiteController::class, 'services'])->name('services.index');
    Route::get('/leistungen/{service:slug}', [SiteController::class, 'service'])->name('services.show');

    Route::get('/schulungen', [SiteController::class, 'trainings'])->name('trainings.index');
    Route::get('/schulungen/{training:slug}', [SiteController::class, 'training'])->name('trainings.show');

    Route::get('/normen', [SiteController::class, 'norms'])->name('norms.index');
    Route::get('/normen/{norm:slug}', [SiteController::class, 'norm'])->name('norms.show');

    Route::get('/berechnungen', [SiteController::class, 'calculators'])->name('calculators');
    Route::get('/simulator', [SiteController::class, 'simulator'])->name('simulator');
    Route::get('/shaker-auslegung', [SiteController::class, 'planner'])->name('planner');
    Route::get('/app', [SiteController::class, 'app'])->name('app');

    Route::get('/kontakt', [ContactController::class, 'show'])->name('contact');
    Route::post('/kontakt', [ContactController::class, 'store'])->middleware('throttle:6,1,contact')->name('contact.store');

    Route::get('/impressum', [SiteController::class, 'imprint'])->name('imprint');
    Route::get('/datenschutz', [SiteController::class, 'privacy'])->name('privacy');

    // Im Admin angelegte Seiten
    Route::get('/seite/{page:slug}', [SiteController::class, 'page'])->name('page');

    // Kundenkonto
    Route::prefix('konto')->name('account.')->middleware(AccountsEnabled::class)->group(function (): void {
        Route::middleware('guest:customer')->group(function (): void {
            Route::get('/registrieren', [AuthController::class, 'showRegister'])->name('register');
            Route::post('/registrieren', [AuthController::class, 'register'])->middleware('throttle:5,1,register')->name('register.store');
            Route::get('/anmelden', [AuthController::class, 'showLogin'])->name('login');
            Route::post('/anmelden', [AuthController::class, 'login'])->middleware('throttle:10,1,login')->name('login.store');
            Route::get('/passwort-vergessen', [AuthController::class, 'showForgot'])->name('password.request');
            Route::post('/passwort-vergessen', [AuthController::class, 'sendResetLink'])->middleware('throttle:5,1,pwmail')->name('password.email');
            Route::get('/passwort-neu/{token}', [AuthController::class, 'showReset'])->name('password.reset');
            Route::post('/passwort-neu', [AuthController::class, 'reset'])->middleware('throttle:5,1,pwreset')->name('password.update');
        });

        Route::middleware('auth:customer')->group(function (): void {
            Route::get('/', [AccountController::class, 'dashboard'])->name('dashboard');
            Route::get('/anfragen/{inquiry}', [AccountController::class, 'inquiry'])->name('inquiry');
            Route::get('/profil', [AccountController::class, 'profile'])->name('profile');
            Route::put('/profil', [AccountController::class, 'updateProfile'])->name('profile.update');
            Route::put('/passwort', [AccountController::class, 'updatePassword'])->name('password.change');
            Route::delete('/', [AccountController::class, 'destroy'])->name('destroy');
            Route::post('/abmelden', [AuthController::class, 'logout'])->name('logout');
        });
    });
};

Route::middleware(SetLocale::class.':de')->group($pages);
Route::prefix('en')->name('en.')->middleware(SetLocale::class.':en')->group($pages);

Route::get('/sitemap.xml', [SiteController::class, 'sitemap'])->name('sitemap');
