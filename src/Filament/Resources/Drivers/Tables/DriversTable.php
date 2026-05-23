<?php

namespace TrackAnyDevice\Admin\Filament\Resources\Drivers\Tables;

use Filament\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class DriversTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')->searchable()->sortable(),
                TextColumn::make('class')->searchable()->wrap(),
                TextColumn::make('stream_channel')->badge(),
                TextColumn::make('version'),
                IconColumn::make('supports_stream')->boolean(),
                IconColumn::make('supports_gsm_commands')->boolean(),
            ])
            ->recordActions([EditAction::make()])
            ->defaultSort('name');
    }
}
