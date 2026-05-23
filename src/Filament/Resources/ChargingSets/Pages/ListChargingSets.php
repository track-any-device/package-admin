<?php

namespace TrackAnyDevice\Admin\Filament\Resources\ChargingSets\Pages;

use TrackAnyDevice\Admin\Filament\Resources\ChargingSets\ChargingSetResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListChargingSets extends ListRecords
{
    protected static string $resource = ChargingSetResource::class;

    protected function getHeaderActions(): array
    {
        return [CreateAction::make()];
    }
}
