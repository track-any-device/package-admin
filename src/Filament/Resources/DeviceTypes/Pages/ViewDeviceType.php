<?php

namespace TrackAnyDevice\Admin\Filament\Resources\DeviceTypes\Pages;

use TrackAnyDevice\Admin\Filament\Resources\DeviceTypes\DeviceTypeResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewDeviceType extends ViewRecord
{
    protected static string $resource = DeviceTypeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
