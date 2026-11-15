<?php

use App\Http\Controllers\ContactController;
use App\Http\Controllers\SiteController;
use Illuminate\Support\Facades\Route;

Route::get('/', [SiteController::class, 'home'])->name('home');

Route::get('/leistungen', [SiteController::class, 'services'])->name('services.index');
Route::get('/leistungen/{service:slug}', [SiteController::class, 'service'])->name('services.show');

Route::get('/schulungen', [SiteController::class, 'trainings'])->name('trainings.index');
Route::get('/schulungen/{training:slug}', [SiteController::class, 'training'])->name('trainings.show');

Route::get('/normen', [SiteController::class, 'norms'])->name('norms.index');
Route::get('/normen/{norm:slug}', [SiteController::class, 'norm'])->name('norms.show');

Route::get('/berechnungen', [SiteController::class, 'calculators'])->name('calculators');

Route::get('/kontakt', [ContactController::class, 'show'])->name('contact');
Route::post('/kontakt', [ContactController::class, 'store'])->middleware('throttle:6,1')->name('contact.store');

Route::get('/impressum', [SiteController::class, 'imprint'])->name('imprint');
Route::get('/datenschutz', [SiteController::class, 'privacy'])->name('privacy');

Route::get('/sitemap.xml', [SiteController::class, 'sitemap'])->name('sitemap');
