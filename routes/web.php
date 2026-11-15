<?php

use App\Http\Controllers\ContactController;
use App\Http\Controllers\SiteController;
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
    Route::get('/app', [SiteController::class, 'app'])->name('app');

    Route::get('/kontakt', [ContactController::class, 'show'])->name('contact');
    Route::post('/kontakt', [ContactController::class, 'store'])->middleware('throttle:6,1')->name('contact.store');

    Route::get('/impressum', [SiteController::class, 'imprint'])->name('imprint');
    Route::get('/datenschutz', [SiteController::class, 'privacy'])->name('privacy');
};

Route::middleware(SetLocale::class.':de')->group($pages);
Route::prefix('en')->name('en.')->middleware(SetLocale::class.':en')->group($pages);

Route::get('/sitemap.xml', [SiteController::class, 'sitemap'])->name('sitemap');
