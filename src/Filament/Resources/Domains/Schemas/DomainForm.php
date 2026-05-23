<?php

namespace TrackAnyDevice\Admin\Filament\Resources\Domains\Schemas;

use TrackAnyDevice\Core\Models\Tenant;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class DomainForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('Domain')->columns(2)->schema([
                Select::make('tenant_id')
                    ->label('Tenant')
                    ->relationship('tenant', 'name')
                    ->getOptionLabelFromRecordUsing(fn (Tenant $record) => $record->name.' ('.$record->slug.')')
                    ->searchable(['name', 'slug'])
                    ->preload()
                    ->required(),

                TextInput::make('domain')
                    ->label('Hostname')
                    ->required()
                    ->maxLength(253)
                    ->placeholder('e.g. acme.fleet-tracking.com')
                    ->helperText('Full hostname (no https://, no trailing slash).')
                    ->unique(table: 'domains', column: 'domain', ignoreRecord: true),

                Toggle::make('is_primary')
                    ->label('Primary domain')
                    ->helperText('The primary hostname used for redirect links and portal URLs. Use the "Set Primary" action on the list to safely switch — toggling here does not unset the previous primary.')
                    ->default(false)
                    ->columnSpanFull(),
            ]),
        ]);
    }
}
