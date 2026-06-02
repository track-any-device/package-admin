<?php

namespace TrackAnyDevice\Admin\Filament\Resources\IncidentLevels\Pages;

use TrackAnyDevice\Admin\Filament\Resources\IncidentLevels\IncidentLevelResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditIncidentLevel extends EditRecord
{
    protected static string $resource = IncidentLevelResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
