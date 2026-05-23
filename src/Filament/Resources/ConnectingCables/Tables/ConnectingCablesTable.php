<?php

namespace TrackAnyDevice\Admin\Filament\Resources\ConnectingCables\Tables;

use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class ConnectingCablesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')->searchable()->sortable(),
                TextColumn::make('connector_type'),
                TextColumn::make('protocol')->badge(),
            ])
            ->recordActions([EditAction::make()])
            ->defaultSort('name');
    }
}
