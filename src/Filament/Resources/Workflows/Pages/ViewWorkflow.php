<?php

namespace TrackAnyDevice\Admin\Filament\Resources\Workflows\Pages;

use TrackAnyDevice\Admin\Filament\Resources\Workflows\WorkflowResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewWorkflow extends ViewRecord
{
    protected static string $resource = WorkflowResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
