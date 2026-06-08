<?php

namespace TrackAnyDevice\Admin\Panels;

use TrackAnyDevice\Admin\DepartmentPanelProvider;
use TrackAnyDevice\Admin\Filament\Resources\ChargingSets\ChargingSetResource;
use TrackAnyDevice\Admin\Filament\Resources\Chips\ChipResource;
use TrackAnyDevice\Admin\Filament\Resources\ComputeBoards\ComputeBoardResource;
use TrackAnyDevice\Admin\Filament\Resources\ConnectingCables\ConnectingCableResource;
use TrackAnyDevice\Admin\Filament\Resources\DeviceTypes\DeviceTypeResource;
use TrackAnyDevice\Admin\Filament\Resources\Drivers\DriverResource;
use TrackAnyDevice\Admin\Filament\Resources\GsmNetworks\GsmNetworkResource;
use TrackAnyDevice\Admin\Filament\Resources\Sensors\SensorResource;
use Filament\Support\Colors\Color;

class EngineeringPanelProvider extends DepartmentPanelProvider
{
    protected static function departmentId(): string
    {
        return 'engineering';
    }

    protected static function departmentResources(): array
    {
        return [
            ChipResource::class,
            ComputeBoardResource::class,
            ConnectingCableResource::class,
            ChargingSetResource::class,
            SensorResource::class,
            GsmNetworkResource::class,
            DriverResource::class,
            DeviceTypeResource::class,
        ];
    }

    protected static function departmentColor(): array
    {
        return [
            'primary' => Color::Sky,
            'success' => Color::Emerald,
            'warning' => Color::Amber,
            'danger' => Color::Red,
            'info' => Color::Indigo,
            'gray' => Color::Neutral,
        ];
    }
}
