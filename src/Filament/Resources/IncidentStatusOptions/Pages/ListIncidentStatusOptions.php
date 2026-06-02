<?php

namespace TrackAnyDevice\Admin\Filament\Resources\IncidentStatusOptions\Pages;

use TrackAnyDevice\Admin\Filament\Resources\IncidentStatusOptions\IncidentStatusOptionResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListIncidentStatusOptions extends ListRecords
{
    protected static string $resource = IncidentStatusOptionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
