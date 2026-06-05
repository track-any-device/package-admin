<?php

namespace TrackAnyDevice\Admin\Filament\Resources\Warehouses\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Illuminate\Support\Str;

class WarehouseForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('Warehouse Details')
                ->columns(2)
                ->schema([
                    TextInput::make('name')
                        ->required()
                        ->maxLength(255)
                        ->live(onBlur: true)
                        ->afterStateUpdated(fn (string $state, callable $set) => $set('code', Str::upper(Str::slug($state, ''))))
                        ->columnSpanFull(),

                    TextInput::make('code')
                        ->required()
                        ->maxLength(20)
                        ->unique(ignoreRecord: true)
                        ->helperText('Short code for quick reference'),

                    Toggle::make('is_active')
                        ->default(true),
                ]),

            Section::make('Location')
                ->columns(2)
                ->collapsed()
                ->schema([
                    TextInput::make('address')
                        ->maxLength(255)
                        ->columnSpanFull(),

                    TextInput::make('city')
                        ->maxLength(255),

                    Select::make('country_id')
                        ->relationship('country', 'name')
                        ->searchable()
                        ->preload()
                        ->nullable(),
                ]),
        ]);
    }
}
