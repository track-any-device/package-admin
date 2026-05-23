<?php

namespace TrackAnyDevice\Admin\Filament\Resources\Chips\Tables;

use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class ChipsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')->searchable()->sortable(),
                TextColumn::make('manufacturer')->searchable(),
                TextColumn::make('type')->badge(),
                TextColumn::make('sensors.name')->badge(),
            ])
            ->recordActions([EditAction::make()])
            ->defaultSort('name');
    }
}
