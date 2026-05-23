<?php

namespace TrackAnyDevice\Admin\Filament\Resources\GsmNetworks\Pages;

use TrackAnyDevice\Admin\Filament\Resources\GsmNetworks\GsmNetworkResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditGsmNetwork extends EditRecord
{
    protected static string $resource = GsmNetworkResource::class;

    protected function getHeaderActions(): array
    {
        return [DeleteAction::make()];
    }
}
