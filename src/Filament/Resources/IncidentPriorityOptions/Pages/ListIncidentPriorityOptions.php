<?php

namespace TrackAnyDevice\Admin\Filament\Resources\IncidentPriorityOptions\Pages;

use TrackAnyDevice\Admin\Filament\Resources\IncidentPriorityOptions\IncidentPriorityOptionResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListIncidentPriorityOptions extends ListRecords
{
    protected static string $resource = IncidentPriorityOptionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
