<?php

namespace TrackAnyDevice\Admin\Filament\Resources\Countries\Tables;

use Filament\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class CountriesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')->searchable()->sortable(),
                TextColumn::make('iso_code')->label('ISO')->sortable(),
                TextColumn::make('country_code')->label('Dial'),
                TextColumn::make('currency_code')->label('Currency'),
                TextColumn::make('conversion_rate')->label('Rate'),
                TextColumn::make('conversion_markup_percent')->label('Markup')->suffix('%'),
                TextColumn::make('sms_gateway')->badge()->placeholder('— observe —'),
                IconColumn::make('is_default')->boolean()->label('Default'),
                IconColumn::make('is_fallback')->boolean()->label('Fallback'),
                IconColumn::make('is_active')->boolean(),
            ])
            ->recordActions([EditAction::make()])
            ->defaultSort('name');
    }
}
