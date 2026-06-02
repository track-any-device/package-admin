<?php

namespace TrackAnyDevice\Admin\Filament\Resources\IncidentStatusOptions\Pages;

use TrackAnyDevice\Admin\Filament\Resources\IncidentStatusOptions\IncidentStatusOptionResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewIncidentStatusOption extends ViewRecord
{
    protected static string $resource = IncidentStatusOptionResource::class;

    protected function getHeaderActions(): array
    {
        return [EditAction::make()];
    }
}
