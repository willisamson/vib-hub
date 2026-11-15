<?php

namespace App\Providers;

use App\Support\Site;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->singleton(Site::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        View::composer(['layouts.*', 'partials.*', 'pages.*', 'services.*', 'trainings.*', 'norms.*', 'components.*'], function ($view): void {
            $view->with('site', $this->app->make(Site::class));
        });
    }
}
