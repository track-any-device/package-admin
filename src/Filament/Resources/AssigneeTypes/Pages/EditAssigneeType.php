<?php

namespace TrackAnyDevice\Admin\Filament\Resources\AssigneeTypes\Pages;

use TrackAnyDevice\Admin\Filament\Resources\AssigneeTypes\AssigneeTypeResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditAssigneeType extends EditRecord
{
    protected static string $resource = AssigneeTypeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
