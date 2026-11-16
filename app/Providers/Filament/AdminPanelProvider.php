<?php

namespace App\Providers\Filament;

use App\Filament\Pages\Auth\Login;
use App\Support\Site;
use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\AuthenticateSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Navigation\NavigationGroup;
use Filament\Navigation\NavigationItem;
use Filament\Pages\Dashboard;
use Filament\Auth\MultiFactor\App\AppAuthentication;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Filament\Support\Icons\Heroicon;
use Filament\View\PanelsRenderHook;
use Filament\Widgets\AccountWidget;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\PreventRequestForgery;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\Support\HtmlString;
use Illuminate\View\Middleware\ShareErrorsFromSession;

class AdminPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->default()
            ->id('admin')
            ->path('admin')
            ->login(Login::class)
            ->profile()
            // Zwei-Faktor-Anmeldung mit Authenticator-App (Pflicht für alle Admins, mit Wiederherstellungscodes)
            ->multiFactorAuthentication([
                AppAuthentication::make()->recoverable()->brandName('vib-hub'),
            ], isRequired: true)
            ->brandName('vib-hub Admin')
            ->brandLogo(fn (): string => app(Site::class)->logoUrl())
            ->darkModeBrandLogo(fn (): string => app(Site::class)->logoOnDarkUrl())
            ->brandLogoHeight('2.1rem')
            ->favicon(fn (): string => app(Site::class)->faviconUrl())
            ->colors([
                'primary' => Color::Cyan,
                'gray' => Color::Slate,
                'warning' => Color::Amber,
                'info' => Color::Violet,
            ])
            ->font('Archivo')
            ->sidebarCollapsibleOnDesktop()
            ->renderHook(PanelsRenderHook::SIMPLE_LAYOUT_START, fn () => view('filament.auth.login-brand'), scopes: Login::class)
            ->renderHook(PanelsRenderHook::AUTH_LOGIN_FORM_AFTER, fn () => new HtmlString('<a class="vh-back" href="'.e(url('/')).'">← Zurück zur Website</a>'))
            ->navigationGroups([
                NavigationGroup::make('Kunden'),
                NavigationGroup::make('Inhalte'),
                NavigationGroup::make('Website'),
                NavigationGroup::make('System'),
            ])
            ->navigationItems([
                NavigationItem::make('Website ansehen')
                    ->url(fn (): string => url('/'), shouldOpenInNewTab: true)
                    ->icon(Heroicon::OutlinedGlobeAlt)
                    ->group('System')
                    ->sort(99),
            ])
            ->discoverResources(in: app_path('Filament/Resources'), for: 'App\Filament\Resources')
            ->discoverPages(in: app_path('Filament/Pages'), for: 'App\Filament\Pages')
            ->pages([
                Dashboard::class,
            ])
            ->discoverWidgets(in: app_path('Filament/Widgets'), for: 'App\Filament\Widgets')
            ->widgets([
                AccountWidget::class,
            ])
            ->middleware([
                EncryptCookies::class,
                AddQueuedCookiesToResponse::class,
                StartSession::class,
                AuthenticateSession::class,
                ShareErrorsFromSession::class,
                PreventRequestForgery::class,
                SubstituteBindings::class,
                DisableBladeIconComponents::class,
                DispatchServingFilamentEvent::class,
            ])
            ->authMiddleware([
                Authenticate::class,
            ]);
    }
}
