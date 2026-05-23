<?php

namespace TrackAnyDevice\Admin\Filament\Resources\Assignees\Pages;

use TrackAnyDevice\Admin\Filament\Resources\Assignees\AssigneeResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditAssignee extends EditRecord
{
    protected static string $resource = AssigneeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
