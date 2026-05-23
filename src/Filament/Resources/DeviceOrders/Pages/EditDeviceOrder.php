<?php

namespace TrackAnyDevice\Admin\Filament\Resources\DeviceOrders\Pages;

use TrackAnyDevice\Admin\Filament\Resources\DeviceOrders\DeviceOrderResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditDeviceOrder extends EditRecord
{
    protected static string $resource = DeviceOrderResource::class;

    protected function getHeaderActions(): array
    {
        return [DeleteAction::make()];
    }
}
