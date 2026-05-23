<?php

namespace TrackAnyDevice\Admin\Filament\Resources\Drivers\Pages;

use TrackAnyDevice\Admin\Filament\Resources\Drivers\DriverResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewDriver extends ViewRecord
{
    protected static string $resource = DriverResource::class;

    protected function getHeaderActions(): array
    {
        return [EditAction::make()];
    }
}
