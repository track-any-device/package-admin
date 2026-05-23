<?php

namespace TrackAnyDevice\Admin\Filament\Resources\Chips\Pages;

use TrackAnyDevice\Admin\Filament\Resources\Chips\ChipResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewChip extends ViewRecord
{
    protected static string $resource = ChipResource::class;

    protected function getHeaderActions(): array
    {
        return [EditAction::make()];
    }
}
