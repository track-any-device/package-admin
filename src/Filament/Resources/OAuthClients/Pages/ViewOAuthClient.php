<?php

namespace TrackAnyDevice\Admin\Filament\Resources\OAuthClients\Pages;

use TrackAnyDevice\Admin\Filament\Resources\OAuthClients\OAuthClientResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewOAuthClient extends ViewRecord
{
    protected static string $resource = OAuthClientResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
