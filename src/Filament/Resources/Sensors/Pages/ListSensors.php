<?php

namespace TrackAnyDevice\Admin\Filament\Resources\Sensors\Pages;

use TrackAnyDevice\Admin\Filament\Resources\Sensors\SensorResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListSensors extends ListRecords
{
    protected static string $resource = SensorResource::class;

    protected function getHeaderActions(): array
    {
        return [CreateAction::make()];
    }
}
