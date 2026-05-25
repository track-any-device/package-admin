<?php

namespace TrackAnyDevice\Admin\Filament\Resources\OAuthClients\Pages;

use TrackAnyDevice\Admin\Filament\Resources\OAuthClients\OAuthClientResource;
use TrackAnyDevice\Admin\Models\OAuthClient;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\CreateRecord;

class CreateOAuthClient extends CreateRecord
{
    protected static string $resource = OAuthClientResource::class;

    private string $plainSecret = '';

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $this->plainSecret = bin2hex(random_bytes(32));
        $data['client_secret_hash'] = bcrypt($this->plainSecret);

        return $data;
    }

    protected function afterCreate(): void
    {
        /** @var OAuthClient $record */
        $record = $this->getRecord();

        Notification::make()
            ->title('OAuth client created — copy your secret now')
            ->body(
                "**Client ID:** `{$record->client_id}`\n\n"
                . "**Secret (shown once):** `{$this->plainSecret}`\n\n"
                . 'This value will never be shown again. Store it in your environment or a secrets manager immediately.'
            )
            ->warning()
            ->persistent()
            ->send();
    }
}
