<?php

namespace TrackAnyDevice\Admin\Filament\Resources\AssigneeTypes\Pages;

use TrackAnyDevice\Admin\Filament\Resources\AssigneeTypes\AssigneeTypeResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListAssigneeTypes extends ListRecords
{
    protected static string $resource = AssigneeTypeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
