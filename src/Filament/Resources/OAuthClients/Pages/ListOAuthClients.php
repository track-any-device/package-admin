<?php

namespace TrackAnyDevice\Admin\Filament\Resources\OAuthClients\Pages;

use TrackAnyDevice\Admin\Filament\Resources\OAuthClients\OAuthClientResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListOAuthClients extends ListRecords
{
    protected static string $resource = OAuthClientResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
