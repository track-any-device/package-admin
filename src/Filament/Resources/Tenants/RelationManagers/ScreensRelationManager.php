<?php

namespace TrackAnyDevice\Admin\Filament\Resources\Tenants\RelationManagers;

use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class ScreensRelationManager extends RelationManager
{
    protected static string $relationship = 'screens';

    public function form(Schema $schema): Schema
    {
        return $schema->components([
            TextInput::make('name')
                ->required()
                ->maxLength(100)
                ->columnSpanFull(),

            Select::make('type')
                ->required()
                ->options([
                    'gis' => 'GIS — Geo Location Map',
                    'bis' => 'BIS — Battery Stats Map',
                    'altitude' => 'Altitude Map',
                    'level' => 'Level Map',
                    'gsm_coverage' => 'GSM Coverage Map',
                    'vibration' => 'Vibration Map',
                    'temperature' => 'Temperature Map',
                ])
                ->columnSpanFull(),

            TextInput::make('sort_order')
                ->label('Order')
                ->numeric()
                ->default(0)
                ->minValue(0),

            Toggle::make('is_active')
                ->label('Active')
                ->default(true),
        ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->reorderable('sort_order')
            ->columns([
                TextColumn::make('name')
                    ->searchable(),

                TextColumn::make('type')
                    ->badge()
                    ->formatStateUsing(fn ($state) => match ($state) {
                        'gis' => 'GIS',
                        'bis' => 'BIS',
                        'altitude' => 'Altitude',
                        'level' => 'Level',
                        'gsm_coverage' => 'GSM Coverage',
                        'vibration' => 'Vibration',
                        'temperature' => 'Temperature',
                        default => $state,
                    })
                    ->color(fn ($state) => match ($state) {
                        'gis' => 'info',
                        'bis' => 'warning',
                        'altitude' => 'primary',
                        'level' => 'success',
                        'gsm_coverage' => 'danger',
                        default => 'gray',
                    }),

                IconColumn::make('is_active')
                    ->label('Active')
                    ->boolean(),

                TextColumn::make('sort_order')
                    ->label('Order')
                    ->sortable(),
            ])
            ->headerActions([
                CreateAction::make()->label('Add Screen'),
            ])
            ->recordActions([
                EditAction::make(),
                DeleteAction::make(),
            ]);
    }
}
