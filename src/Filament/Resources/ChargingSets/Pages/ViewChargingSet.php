<?php

namespace TrackAnyDevice\Admin\Filament\Resources\ChargingSets\Pages;

use TrackAnyDevice\Admin\Filament\Resources\ChargingSets\ChargingSetResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewChargingSet extends ViewRecord
{
    protected static string $resource = ChargingSetResource::class;

    protected function getHeaderActions(): array
    {
        return [EditAction::make()];
    }
}
