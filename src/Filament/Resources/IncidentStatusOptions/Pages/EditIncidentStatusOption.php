<?php

namespace TrackAnyDevice\Admin\Filament\Resources\IncidentStatusOptions\Pages;

use TrackAnyDevice\Admin\Filament\Resources\IncidentStatusOptions\IncidentStatusOptionResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditIncidentStatusOption extends EditRecord
{
    protected static string $resource = IncidentStatusOptionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
