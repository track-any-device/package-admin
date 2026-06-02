<?php

namespace TrackAnyDevice\Admin\Filament\Resources\IncidentPriorityOptions\Pages;

use TrackAnyDevice\Admin\Filament\Resources\IncidentPriorityOptions\IncidentPriorityOptionResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewIncidentPriorityOption extends ViewRecord
{
    protected static string $resource = IncidentPriorityOptionResource::class;

    protected function getHeaderActions(): array
    {
        return [EditAction::make()];
    }
}
