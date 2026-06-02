<?php

namespace TrackAnyDevice\Admin;

use TrackAnyDevice\Admin\Http\Middleware\EnsureAdminDomain;
use TrackAnyDevice\Admin\Http\Middleware\AdminAuthenticate;
use Filament\Http\Middleware\AuthenticateSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Pages\Dashboard;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Filament\Widgets\AccountWidget;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\PreventRequestForgery;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;

/**
 * Filament admin panel.
 *
 * Hosted at admin.{APP_DOMAIN} (env ADMIN_DOMAIN) when configured. The
 * panel keeps its own login form — admin is intentionally NOT routed
 * through the login.* OAuth identity provider so it remains usable as a
 * de-facto break-glass surface if the OAuth pipeline is misconfigured.
 *
 * When ADMIN_DOMAIN is unset (local dev, CI), the panel falls back to
 * the bare central host and the legacy /admin path — see EnsureAdminDomain.
 */
class AdminPanelProvider extends PanelProvider
{
    public function boot(): void
    {
        $this->loadViewsFrom(__DIR__.'/resources/views', 'filament');
    }

    public function panel(Panel $panel): Panel
    {
        $adminDomain = (string) env('ADMIN_DOMAIN', '');

        // Dedicated host: serve at the root of admin.{APP_DOMAIN}. Without
        // ADMIN_DOMAIN we fall back to the legacy central/admin layout so
        // local dev and CI continue to work without env changes.
        if ($adminDomain !== '') {
            $panel->domain($adminDomain)->path('');
        } else {
            $panel->path('admin');
        }

        return $panel
            ->default()
            ->id('admin')
            
            ->colors([
                // Match the platform default `--primary` token (defined in
                // resources/css/themes.css under data-theme="default" / blue).
                // Admin chrome is intentionally brand-neutral — tenants run
                // on their own subdomains and never see this surface, so we
                // don't need per-tenant theming here.
                'primary' => Color::hex('#2563eb'),
                'success' => Color::Emerald,
                'warning' => Color::Amber,
                'danger' => Color::Red,
                'info' => Color::Sky,
                'gray' => Color::Neutral,
            ])
            ->discoverResources(in: __DIR__.'/Filament/Resources', for: 'TrackAnyDevice\Admin\Filament\Resources')
            ->discoverPages(in: __DIR__.'/Filament/Pages', for: 'TrackAnyDevice\Admin\Filament\Pages')
            ->pages([
                Dashboard::class,
            ])
            ->discoverWidgets(in: __DIR__.'/Filament/Widgets', for: 'TrackAnyDevice\Admin\Filament\Widgets')
            ->widgets([
                AccountWidget::class,
            ])
            ->middleware([
                // Gate to admin.{APP_DOMAIN} when configured; otherwise
                // fall back to any central host. Tenant subdomains 404.
                EnsureAdminDomain::class,
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
                AdminAuthenticate::class,
            ]);
    }
}
