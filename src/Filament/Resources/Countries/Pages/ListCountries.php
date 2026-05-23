<?php

namespace TrackAnyDevice\Admin\Filament\Resources\Countries\Pages;

use TrackAnyDevice\Admin\Filament\Resources\Countries\CountryResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListCountries extends ListRecords
{
    protected static string $resource = CountryResource::class;

    protected function getHeaderActions(): array
    {
        return [CreateAction::make()];
    }
}
