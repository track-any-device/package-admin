<?php

namespace TrackAnyDevice\Admin\Filament\Resources\ConnectingCables\Pages;

use TrackAnyDevice\Admin\Filament\Resources\ConnectingCables\ConnectingCableResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditConnectingCable extends EditRecord
{
    protected static string $resource = ConnectingCableResource::class;

    protected function getHeaderActions(): array
    {
        return [DeleteAction::make()];
    }
}
