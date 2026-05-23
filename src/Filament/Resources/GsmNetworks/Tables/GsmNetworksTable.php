<?php

namespace TrackAnyDevice\Admin\Filament\Resources\GsmNetworks\Tables;

use Filament\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class GsmNetworksTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')->searchable()->sortable(),
                TextColumn::make('country.name')->label('Country')->sortable(),
                TextColumn::make('country_code')->label('ISO3')->sortable()->toggleable(),
                TextColumn::make('apn')->searchable(),
                IconColumn::make('is_active')->boolean(),
            ])
            ->recordActions([EditAction::make()])
            ->defaultSort('name');
    }
}
