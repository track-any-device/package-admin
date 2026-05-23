<?php

namespace TrackAnyDevice\Admin\Filament\Resources\Cms\IndustryResource\Pages;

use TrackAnyDevice\Admin\Filament\Resources\Cms\IndustryResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListIndustries extends ListRecords
{
    protected static string $resource = IndustryResource::class;

    protected function getHeaderActions(): array
    {
        return [CreateAction::make()];
    }
}
