<?php

namespace TrackAnyDevice\Admin\Filament\Resources\BeatTemplates\Pages;

use TrackAnyDevice\Admin\Filament\Resources\BeatTemplates\BeatTemplateResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditBeatTemplate extends EditRecord
{
    protected static string $resource = BeatTemplateResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
