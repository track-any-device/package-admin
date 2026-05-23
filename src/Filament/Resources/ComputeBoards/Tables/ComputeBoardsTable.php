<?php

namespace TrackAnyDevice\Admin\Filament\Resources\ComputeBoards\Tables;

use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class ComputeBoardsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')->searchable()->sortable(),
                TextColumn::make('manufacturer')->searchable(),
                TextColumn::make('mcu'),
                TextColumn::make('operating_voltage')->label('Voltage'),
            ])
            ->recordActions([EditAction::make()])
            ->defaultSort('name');
    }
}
