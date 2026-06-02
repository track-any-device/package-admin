<?php

namespace TrackAnyDevice\Admin\Filament\Resources\Workflows\Pages;

use TrackAnyDevice\Admin\Filament\Resources\Workflows\WorkflowResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListWorkflows extends ListRecords
{
    protected static string $resource = WorkflowResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
