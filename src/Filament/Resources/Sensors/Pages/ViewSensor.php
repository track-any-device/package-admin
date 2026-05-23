<?php

namespace TrackAnyDevice\Admin\Filament\Resources\Sensors\Pages;

use TrackAnyDevice\Admin\Filament\Resources\Sensors\SensorResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewSensor extends ViewRecord
{
    protected static string $resource = SensorResource::class;

    protected function getHeaderActions(): array
    {
        return [EditAction::make()];
    }
}
