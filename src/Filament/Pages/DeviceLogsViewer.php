<?php

namespace TrackAnyDevice\Admin\Filament\Pages;

use BackedEnum;
use Filament\Pages\Page;
use Filament\Support\Icons\Heroicon;

/**
 * Runtime device-connection log viewer for admins.
 *
 * Subscribes the admin browser to the `private-admin.device-logs`
 * broadcast channel and renders incoming entries in a ring buffer
 * — no persistence, no DB writes. Purely an integration-debugging
 * window.
 *
 * Tenants see a similar surface inside their operational portal at
 * /device-logs (scoped to their tenant channel only).
 */
class DeviceLogsViewer extends Page
{
    protected string $view = 'filament.pages.device-logs-viewer';

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedCommandLine;

    protected static string|\UnitEnum|null $navigationGroup = 'Operations';

    protected static ?int $navigationSort = 99;

    protected static ?string $title = 'Device Logs';

    protected static ?string $navigationLabel = 'Device Logs';

    protected static ?string $slug = 'device-logs';
}
