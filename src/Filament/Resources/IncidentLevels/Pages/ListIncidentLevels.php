<?php

namespace TrackAnyDevice\Admin\Filament\Resources\IncidentLevels\Pages;

use TrackAnyDevice\Admin\Filament\Resources\IncidentLevels\IncidentLevelResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListIncidentLevels extends ListRecords
{
    protected static string $resource = IncidentLevelResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
