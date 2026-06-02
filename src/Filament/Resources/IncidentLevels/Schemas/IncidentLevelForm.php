<?php

namespace TrackAnyDevice\Admin\Filament\Resources\IncidentLevels\Schemas;

use TrackAnyDevice\Core\Models\Tenant;
use Filament\Forms\Components\ColorPicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class IncidentLevelForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Incident Level')
                    ->columns(2)
                    ->schema([
                        TextInput::make('label')
                            ->required()
                            ->maxLength(100),
                        TextInput::make('level_number')
                            ->numeric()
                            ->required(),
                        ColorPicker::make('color'),
                        Select::make('tenant_id')
                            ->label('Tenant')
                            ->relationship('tenant', 'name')
                            ->searchable()
                            ->preload()
                            ->placeholder('Platform default'),
                        Textarea::make('description')
                            ->rows(2)
                            ->columnSpanFull(),
                    ]),
            ]);
    }
}
