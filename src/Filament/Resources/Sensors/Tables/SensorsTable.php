<?php

namespace TrackAnyDevice\Admin\Filament\Resources\Sensors\Tables;

use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class SensorsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')->searchable()->sortable(),
                TextColumn::make('slug')->searchable(),
                TextColumn::make('data_type')->badge(),
                TextColumn::make('unit'),
                TextColumn::make('sort_order')->sortable(),
            ])
            ->recordActions([EditAction::make()])
            ->defaultSort('sort_order');
    }
}
