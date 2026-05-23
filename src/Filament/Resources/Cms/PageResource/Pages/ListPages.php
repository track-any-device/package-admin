<?php

namespace TrackAnyDevice\Admin\Filament\Resources\Cms\PageResource\Pages;

use TrackAnyDevice\Admin\Filament\Resources\Cms\PageResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListPages extends ListRecords
{
    protected static string $resource = PageResource::class;

    protected function getHeaderActions(): array
    {
        return [CreateAction::make()];
    }
}
