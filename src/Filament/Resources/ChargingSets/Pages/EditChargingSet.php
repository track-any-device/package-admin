<?php

namespace TrackAnyDevice\Admin\Filament\Resources\ChargingSets\Pages;

use TrackAnyDevice\Admin\Filament\Resources\ChargingSets\ChargingSetResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditChargingSet extends EditRecord
{
    protected static string $resource = ChargingSetResource::class;

    protected function getHeaderActions(): array
    {
        return [DeleteAction::make()];
    }
}
