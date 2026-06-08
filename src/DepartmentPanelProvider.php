<?php

namespace TrackAnyDevice\Admin;

use TrackAnyDevice\Admin\Http\Middleware\AdminAuthenticate;
use TrackAnyDevice\Admin\Http\Middleware\EnsureAdminDomain;
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

abstract class DepartmentPanelProvider extends PanelProvider
{
    public function boot(): void
    {
        $this->loadViewsFrom(__DIR__.'/resources/views', 'filament');
    }

    abstract protected static function departmentId(): string;

    /** @return class-string[] */
    abstract protected static function departmentResources(): array;

    /** @return array<string, Color|string|array> */
    abstract protected static function departmentColor(): array;

    /** @return class-string[] */
    protected static function departmentPages(): array
    {
        return [];
    }

    public function panel(Panel $panel): Panel
    {
        $adminDomain = (string) env('ADMIN_DOMAIN', '');
        $id = static::departmentId();

        if ($adminDomain !== '') {
            $panel->domain($adminDomain)->path($id);
        } else {
            $panel->path($id);
        }

        return $panel
            ->id($id)
            ->colors(static::departmentColor())
            ->resources(static::departmentResources())
            ->pages(array_merge([Dashboard::class], static::departmentPages()))
            ->widgets([
                AccountWidget::class,
            ])
            ->middleware([
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
