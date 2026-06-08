<?php

namespace TrackAnyDevice\Admin\Panels;

use TrackAnyDevice\Admin\DepartmentPanelProvider;
use TrackAnyDevice\Admin\Filament\Resources\Devices\DeviceResource;
use TrackAnyDevice\Admin\Filament\Resources\DeviceTypes\DeviceTypeResource;
use TrackAnyDevice\Admin\Filament\Resources\PurchaseOrders\PurchaseOrderResource;
use Filament\Support\Colors\Color;

class ProcurementPanelProvider extends DepartmentPanelProvider
{
    protected static function departmentId(): string
    {
        return 'procurement';
    }

    protected static function departmentResources(): array
    {
        return [
            DeviceResource::class,
            PurchaseOrderResource::class,
            DeviceTypeResource::class,
        ];
    }

    protected static function departmentColor(): array
    {
        return [
            'primary' => Color::Amber,
            'success' => Color::Emerald,
            'warning' => Color::Orange,
            'danger' => Color::Red,
            'info' => Color::Sky,
            'gray' => Color::Neutral,
        ];
    }
}
