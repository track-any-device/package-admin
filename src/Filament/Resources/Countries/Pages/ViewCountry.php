<?php

namespace TrackAnyDevice\Admin\Filament\Resources\Countries\Pages;

use TrackAnyDevice\Admin\Filament\Resources\Countries\CountryResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewCountry extends ViewRecord
{
    protected static string $resource = CountryResource::class;

    protected function getHeaderActions(): array
    {
        return [EditAction::make()];
    }
}
