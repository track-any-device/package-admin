<?php

namespace TrackAnyDevice\Admin\Filament\Resources\GsmNetworks\Pages;

use TrackAnyDevice\Admin\Filament\Resources\GsmNetworks\GsmNetworkResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewGsmNetwork extends ViewRecord
{
    protected static string $resource = GsmNetworkResource::class;

    protected function getHeaderActions(): array
    {
        return [EditAction::make()];
    }
}
