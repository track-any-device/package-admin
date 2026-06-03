<?php

namespace TrackAnyDevice\Admin\Filament\Resources\Tenants\RelationManagers;

use TrackAnyDevice\Core\Models\TenantApiKey;
use Filament\Actions\Action;
use Filament\Actions\DeleteAction;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

/**
 * Manages machine API keys for a tenant's server-tenant portal instances.
 *
 * Keys are bcrypt-hashed — the plain text is shown ONCE via a persistent
 * notification immediately after generation. Copy it before dismissing.
 * There is no way to recover the plain key after that point; generate a
 * new key and revoke the old one if it is lost.
 */
class TenantApiKeysRelationManager extends RelationManager
{
    protected static string $relationship = 'apiKeys';

    protected static ?string $title = 'Portal API Keys';

    public function form(Schema $schema): Schema
    {
        // No editable form — keys are generated, not edited.
        return $schema->components([]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('name')
            ->columns([
                TextColumn::make('name')
                    ->label('Key name')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('created_at')
                    ->label('Generated')
                    ->dateTime('d M Y, H:i')
                    ->sortable(),

                TextColumn::make('last_used_at')
                    ->label('Last used')
                    ->dateTime('d M Y, H:i')
                    ->placeholder('Never')
                    ->sortable(),
            ])
            ->headerActions([
                Action::make('generate')
                    ->label('Generate API Key')
                    ->icon('heroicon-o-key')
                    ->color('primary')
                    ->form([
                        TextInput::make('name')
                            ->label('Key name')
                            ->helperText('A label to identify this key — e.g. "Default", "On-Premise".')
                            ->default('Default')
                            ->required()
                            ->maxLength(100),
                    ])
                    ->action(function (array $data, RelationManager $livewire): void {
                        $tenant = $livewire->getOwnerRecord();

                        ['plain_key' => $plainKey] = TenantApiKey::generate($tenant, $data['name']);

                        Notification::make()
                            ->title('API Key Generated — Copy Now')
                            ->body(
                                "This key will **not** be shown again.\n\n" .
                                "```\n{$plainKey}\n```\n\n" .
                                "Set it as APP_TENANT_API_KEY in the server-tenant container."
                            )
                            ->warning()
                            ->persistent()
                            ->send();
                    }),
            ])
            ->recordActions([
                DeleteAction::make()
                    ->label('Revoke')
                    ->modalHeading('Revoke API Key')
                    ->modalDescription(
                        'This permanently revokes the key. Any server-tenant container ' .
                        'using it will lose access immediately. Generate a replacement first.'
                    )
                    ->successNotificationTitle('API key revoked'),
            ])
            ->emptyStateHeading('No API keys yet')
            ->emptyStateDescription('Generate an API key to allow a server-tenant portal to connect to the central platform.')
            ->emptyStateActions([
                Action::make('generate_first')
                    ->label('Generate First Key')
                    ->icon('heroicon-o-key')
                    ->color('primary')
                    ->form([
                        TextInput::make('name')
                            ->label('Key name')
                            ->default('Default')
                            ->required()
                            ->maxLength(100),
                    ])
                    ->action(function (array $data, RelationManager $livewire): void {
                        $tenant = $livewire->getOwnerRecord();
                        ['plain_key' => $plainKey] = TenantApiKey::generate($tenant, $data['name']);

                        Notification::make()
                            ->title('API Key Generated — Copy Now')
                            ->body(
                                "This key will **not** be shown again.\n\n" .
                                "```\n{$plainKey}\n```\n\n" .
                                "Set it as APP_TENANT_API_KEY in the server-tenant container."
                            )
                            ->warning()
                            ->persistent()
                            ->send();
                    }),
            ]);
    }
}
