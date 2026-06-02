<?php

namespace TrackAnyDevice\Admin\Filament\Resources\Workflows\Pages;

use TrackAnyDevice\Admin\Filament\Resources\Workflows\WorkflowResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditWorkflow extends EditRecord
{
    protected static string $resource = WorkflowResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
