<?php

namespace TrackAnyDevice\Admin\Filament\Resources\Beats\Pages;

use TrackAnyDevice\Admin\Filament\Resources\Beats\BeatResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditBeat extends EditRecord
{
    protected static string $resource = BeatResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
