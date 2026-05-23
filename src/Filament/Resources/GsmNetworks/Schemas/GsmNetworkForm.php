<?php

namespace TrackAnyDevice\Admin\Filament\Resources\GsmNetworks\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class GsmNetworkForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('Carrier')->columns(2)->schema([
                TextInput::make('name')->required(),
                Select::make('country_id')
                    ->relationship('country', 'name')
                    ->searchable()
                    ->preload()
                    ->required(),
                TextInput::make('country_code')->label('Legacy Country Code')->required()->maxLength(3)->placeholder('PAK'),
                TextInput::make('apn')->required()->placeholder('jazz.net.pk'),
                Toggle::make('is_active')->default(true),
                TextInput::make('apn_username')->nullable(),
                TextInput::make('apn_password')->nullable(),
            ]),
        ]);
    }
}
