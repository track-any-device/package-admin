<?php

namespace TrackAnyDevice\Admin\Filament\Resources\Assignees\Pages;

use TrackAnyDevice\Admin\Filament\Resources\Assignees\AssigneeResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListAssignees extends ListRecords
{
    protected static string $resource = AssigneeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
