<?php

namespace TrackAnyDevice\Admin\Filament\Resources\OAuthClients\Schemas;

use TrackAnyDevice\Core\Enums\OAuthClientKind;
use TrackAnyDevice\Core\Models\Tenant;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TagsInput;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Illuminate\Support\Str;

class OAuthClientForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Client Details')
                    ->columns(2)
                    ->schema([
                        TextInput::make('name')
                            ->required()
                            ->maxLength(191)
                            ->columnSpanFull(),

                        Select::make('kind')
                            ->label('Kind')
                            ->options(collect(OAuthClientKind::cases())->mapWithKeys(
                                fn (OAuthClientKind $k) => [$k->value => $k->label()]
                            )->all())
                            ->required()
                            ->native(false)
                            ->live()
                            ->helperText('Tenant clients are auto-created by the system when a new organisation is provisioned — creating one manually is only needed in exceptional cases.')
                            ->disabledOn('edit'),

                        // Shown on edit as a read-only badge since kind cannot change.
                        Placeholder::make('kind_display')
                            ->label('Kind')
                            ->content(fn ($record) => $record?->kind?->label() ?? '—')
                            ->hiddenOn('create'),

                        Select::make('tenant_id')
                            ->label('Tenant')
                            ->relationship('tenant', 'name')
                            ->searchable()
                            ->preload()
                            ->nullable()
                            ->helperText('Required when kind is Tenant — one OAuth client per organisation.')
                            ->visible(fn ($get) => $get('kind') === OAuthClientKind::Tenant->value)
                            ->hiddenOn('edit')
                            ->columnSpanFull(),
                    ]),

                Section::make('Credentials')
                    ->columns(2)
                    ->schema([
                        TextInput::make('client_id')
                            ->label('Client ID')
                            ->required()
                            ->maxLength(64)
                            ->default(fn () => 'tci_' . Str::random(28))
                            ->helperText('Public OAuth2 client_id. Auto-generated — you may customise it before saving. Cannot be changed after creation.')
                            ->disabledOn('edit')
                            ->dehydratedWhenDisabled()
                            ->copyable()
                            ->columnSpanFull(),

                        Placeholder::make('secret_note')
                            ->label('Client Secret')
                            ->content('The plain secret is shown once immediately after the client is created and is not stored. Use the "Regenerate Secret" action on this page to issue a new one.')
                            ->columnSpanFull(),
                    ]),

                Section::make('Configuration')
                    ->columns(2)
                    ->schema([
                        TagsInput::make('redirect_uris')
                            ->label('Allowed Redirect URIs')
                            ->placeholder('https://example.com/sso/callback')
                            ->helperText('Exact URIs permitted as the OAuth2 redirect_uri parameter. Add one per line or press Enter between entries.')
                            ->columnSpanFull(),

                        TextInput::make('logout_webhook_url')
                            ->label('Back-Channel Logout URL')
                            ->url()
                            ->maxLength(512)
                            ->nullable()
                            ->placeholder('https://example.com/sso/logout')
                            ->helperText('The platform will POST a signed logout event here when the user\'s identity session ends. Leave blank to skip back-channel single sign-out.')
                            ->columnSpanFull(),

                        Toggle::make('is_active')
                            ->label('Active')
                            ->helperText('Disable to suspend this client without deleting it. Suspended clients are rejected at the /oauth/authorize endpoint.')
                            ->default(true)
                            ->columnSpanFull(),
                    ]),
            ]);
    }
}
