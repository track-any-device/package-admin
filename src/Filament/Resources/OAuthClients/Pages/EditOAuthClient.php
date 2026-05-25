<?php

namespace TrackAnyDevice\Admin\Filament\Resources\OAuthClients\Pages;

use TrackAnyDevice\Admin\Filament\Resources\OAuthClients\OAuthClientResource;
use TrackAnyDevice\Admin\Models\OAuthClient;
use Filament\Actions\Action;
use Filament\Actions\DeleteAction;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\EditRecord;

class EditOAuthClient extends EditRecord
{
    protected static string $resource = OAuthClientResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Action::make('regenerate_secret')
                ->label('Regenerate Secret')
                ->icon('heroicon-o-key')
                ->color('warning')
                ->requiresConfirmation()
                ->modalHeading('Regenerate client secret?')
                ->modalDescription(
                    'A new secret will be generated immediately. The old secret stops working right away. '
                    . 'The new plain value is shown once — copy it before closing this notification.'
                )
                ->action(function (): void {
                    /** @var OAuthClient $record */
                    $record = $this->getRecord();
                    $plain = bin2hex(random_bytes(32));
                    $record->update(['client_secret_hash' => bcrypt($plain)]);

                    Notification::make()
                        ->title('Secret regenerated — copy it now')
                        ->body(
                            "**Client ID:** `{$record->client_id}`\n\n"
                            . "**New secret (shown once):** `{$plain}`\n\n"
                            . 'Update your environment variables before this notification is dismissed.'
                        )
                        ->warning()
                        ->persistent()
                        ->send();
                }),

            DeleteAction::make(),
        ];
    }
}
