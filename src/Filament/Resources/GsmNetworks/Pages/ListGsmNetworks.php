<?php

namespace TrackAnyDevice\Admin\Filament\Resources\GsmNetworks\Pages;

use TrackAnyDevice\Admin\Filament\Resources\GsmNetworks\GsmNetworkResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListGsmNetworks extends ListRecords
{
    protected static string $resource = GsmNetworkResource::class;

    protected function getHeaderActions(): array
    {
        return [CreateAction::make()];
    }
}
