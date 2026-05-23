<?php

namespace TrackAnyDevice\Admin\Filament\Resources\Assignees\Pages;

use TrackAnyDevice\Admin\Filament\Resources\Assignees\AssigneeResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewAssignee extends ViewRecord
{
    protected static string $resource = AssigneeResource::class;

    protected function getHeaderActions(): array
    {
        return [EditAction::make()];
    }
}
