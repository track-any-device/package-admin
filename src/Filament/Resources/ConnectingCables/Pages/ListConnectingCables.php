<?php

namespace TrackAnyDevice\Admin\Filament\Resources\ConnectingCables\Pages;

use TrackAnyDevice\Admin\Filament\Resources\ConnectingCables\ConnectingCableResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListConnectingCables extends ListRecords
{
    protected static string $resource = ConnectingCableResource::class;

    protected function getHeaderActions(): array
    {
        return [CreateAction::make()];
    }
}
