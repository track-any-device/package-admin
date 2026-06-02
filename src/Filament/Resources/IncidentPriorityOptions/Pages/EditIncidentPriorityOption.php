<?php

namespace TrackAnyDevice\Admin\Filament\Resources\IncidentPriorityOptions\Pages;

use TrackAnyDevice\Admin\Filament\Resources\IncidentPriorityOptions\IncidentPriorityOptionResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditIncidentPriorityOption extends EditRecord
{
    protected static string $resource = IncidentPriorityOptionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
