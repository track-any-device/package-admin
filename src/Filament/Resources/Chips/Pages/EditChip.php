<?php

namespace TrackAnyDevice\Admin\Filament\Resources\Chips\Pages;

use TrackAnyDevice\Admin\Filament\Resources\Chips\ChipResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditChip extends EditRecord
{
    protected static string $resource = ChipResource::class;

    protected function getHeaderActions(): array
    {
        return [DeleteAction::make()];
    }
}
