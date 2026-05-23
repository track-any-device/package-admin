<?php

namespace TrackAnyDevice\Admin\Filament\Resources\Cms\PageResource\Pages;

use TrackAnyDevice\Admin\Filament\Resources\Cms\PageResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewPage extends ViewRecord
{
    protected static string $resource = PageResource::class;

    protected function getHeaderActions(): array
    {
        return [EditAction::make()];
    }
}
