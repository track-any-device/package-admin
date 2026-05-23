<?php

namespace TrackAnyDevice\Admin\Filament\Resources\ConnectingCables\Pages;

use TrackAnyDevice\Admin\Filament\Resources\ConnectingCables\ConnectingCableResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewConnectingCable extends ViewRecord
{
    protected static string $resource = ConnectingCableResource::class;

    protected function getHeaderActions(): array
    {
        return [EditAction::make()];
    }
}
