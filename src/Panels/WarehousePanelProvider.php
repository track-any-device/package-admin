<?php

namespace TrackAnyDevice\Admin\Panels;

use TrackAnyDevice\Admin\DepartmentPanelProvider;
use TrackAnyDevice\Admin\Filament\Pages\DeviceSetup;
use TrackAnyDevice\Admin\Filament\Resources\WarehouseLogs\WarehouseLogResource;
use TrackAnyDevice\Admin\Filament\Resources\Warehouses\WarehouseResource;
use Filament\Support\Colors\Color;

class WarehousePanelProvider extends DepartmentPanelProvider
{
    protected static function departmentId(): string
    {
        return 'warehouse';
    }

    protected static function departmentResources(): array
    {
        return [
            WarehouseResource::class,
            WarehouseLogResource::class,
        ];
    }

    protected static function departmentPages(): array
    {
        return [
            DeviceSetup::class,
        ];
    }

    protected static function departmentColor(): array
    {
        return [
            'primary' => Color::Emerald,
            'success' => Color::Green,
            'warning' => Color::Amber,
            'danger' => Color::Red,
            'info' => Color::Sky,
            'gray' => Color::Neutral,
        ];
    }
}
