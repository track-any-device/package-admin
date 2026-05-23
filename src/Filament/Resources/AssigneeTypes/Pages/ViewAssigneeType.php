<?php

namespace TrackAnyDevice\Admin\Filament\Resources\AssigneeTypes\Pages;

use TrackAnyDevice\Admin\Filament\Resources\AssigneeTypes\AssigneeTypeResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewAssigneeType extends ViewRecord
{
    protected static string $resource = AssigneeTypeResource::class;

    protected function getHeaderActions(): array
    {
        return [EditAction::make()];
    }
}
