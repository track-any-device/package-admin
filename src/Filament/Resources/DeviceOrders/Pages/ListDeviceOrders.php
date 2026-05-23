<?php

namespace TrackAnyDevice\Admin\Filament\Resources\DeviceOrders\Pages;

use TrackAnyDevice\Admin\Filament\Resources\DeviceOrders\DeviceOrderResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListDeviceOrders extends ListRecords
{
    protected static string $resource = DeviceOrderResource::class;

    protected function getHeaderActions(): array
    {
        return [CreateAction::make()];
    }
}
