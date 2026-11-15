<?php

namespace App\Providers;

use App\Support\Menu;
use App\Support\Site;
use Illuminate\Support\Facades\Auth;
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
        $this->app->singleton(Menu::class);

        require_once app_path('Support/helpers.php');
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        View::composer(['layouts.*', 'partials.*', 'pages.*', 'services.*', 'trainings.*', 'norms.*', 'account.*', 'components.*'], function ($view): void {
            $view->with('site', $this->app->make(Site::class));
        });

        View::composer(['partials.header', 'partials.footer'], function ($view): void {
            $view->with('menu', $this->app->make(Menu::class));
            $view->with('customer', Auth::guard('customer')->user());
        });
    }
}
