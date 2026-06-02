<?php

namespace TrackAnyDevice\Admin\Filament\Resources\IncidentPriorityOptions\Schemas;

use Filament\Forms\Components\ColorPicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class IncidentPriorityOptionForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Priority Option')
                    ->columns(2)
                    ->schema([
                        TextInput::make('key')
                            ->required()
                            ->maxLength(50)
                            ->unique(ignoreRecord: true),
                        TextInput::make('label')
                            ->required()
                            ->maxLength(100),
                        ColorPicker::make('color'),
                        TextInput::make('sort_order')
                            ->numeric()
                            ->default(0),
                        Toggle::make('is_default')
                            ->default(false),
                        Select::make('tenant_id')
                            ->relationship('tenant', 'name')
                            ->searchable()
                            ->placeholder('Platform default')
                            ->columnSpanFull(),
                    ]),
            ]);
    }
}
