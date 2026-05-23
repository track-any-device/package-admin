<?php

namespace TrackAnyDevice\Admin\Filament\Resources\ChargingSets\Tables;

use Filament\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class ChargingSetsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')->searchable()->sortable(),
                TextColumn::make('connector'),
                TextColumn::make('voltage')->suffix(' V'),
                TextColumn::make('current_ma')->suffix(' mA')->label('Current'),
                IconColumn::make('wireless')->boolean(),
            ])
            ->recordActions([EditAction::make()])
            ->defaultSort('name');
    }
}
