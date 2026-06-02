<?php

namespace TrackAnyDevice\Admin\Filament\Resources\IncidentLevels\Pages;

use TrackAnyDevice\Admin\Filament\Resources\IncidentLevels\IncidentLevelResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewIncidentLevel extends ViewRecord
{
    protected static string $resource = IncidentLevelResource::class;

    protected function getHeaderActions(): array
    {
        return [EditAction::make()];
    }
}
