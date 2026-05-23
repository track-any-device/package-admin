<?php

namespace TrackAnyDevice\Admin\Filament\Resources\Beats\Pages;

use TrackAnyDevice\Admin\Filament\Resources\Beats\BeatResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListBeats extends ListRecords
{
    protected static string $resource = BeatResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
