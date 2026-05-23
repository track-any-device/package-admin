<?php

namespace TrackAnyDevice\Admin\Filament\Resources\BeatTemplates\Pages;

use TrackAnyDevice\Admin\Filament\Resources\BeatTemplates\BeatTemplateResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListBeatTemplates extends ListRecords
{
    protected static string $resource = BeatTemplateResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
