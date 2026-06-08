<?php

namespace TrackAnyDevice\Admin\Panels;

use TrackAnyDevice\Admin\DepartmentPanelProvider;
use TrackAnyDevice\Admin\Filament\Resources\DeviceOrders\DeviceOrderResource;
use TrackAnyDevice\Admin\Filament\Resources\Products\ProductResource;
use TrackAnyDevice\Admin\Filament\Resources\Tenants\TenantResource;
use Filament\Support\Colors\Color;

class SalesPanelProvider extends DepartmentPanelProvider
{
    protected static function departmentId(): string
    {
        return 'sales';
    }

    protected static function departmentResources(): array
    {
        return [
            DeviceOrderResource::class,
            TenantResource::class,
            ProductResource::class,
        ];
    }

    protected static function departmentColor(): array
    {
        return [
            'primary' => Color::Rose,
            'success' => Color::Emerald,
            'warning' => Color::Amber,
            'danger' => Color::Red,
            'info' => Color::Sky,
            'gray' => Color::Neutral,
        ];
    }
}
