<?php

namespace TrackAnyDevice\Admin\Filament\Resources\DeviceTypes\Pages;

use TrackAnyDevice\Admin\Filament\Resources\DeviceTypes\DeviceTypeResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditDeviceType extends EditRecord
{
    protected static string $resource = DeviceTypeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
