<?php

namespace TrackAnyDevice\Admin\Filament\Resources\Domains\Pages;

use TrackAnyDevice\Admin\Filament\Resources\Domains\DomainResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewDomain extends ViewRecord
{
    protected static string $resource = DomainResource::class;

    protected function getHeaderActions(): array
    {
        return [EditAction::make()];
    }
}
