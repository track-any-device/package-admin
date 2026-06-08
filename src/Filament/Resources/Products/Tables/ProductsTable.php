<?php

namespace TrackAnyDevice\Admin\Filament\Resources\Products\Tables;

use Filament\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class ProductsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')->searchable()->sortable(),
                TextColumn::make('sku')->searchable(),
                TextColumn::make('product_type')->badge(),
                TextColumn::make('price')->money(fn ($record) => $record->currency)->sortable(),
                TextColumn::make('stock')->sortable(),
                TextColumn::make('max_order_quantity')
                    ->label('Max Qty')
                    ->sortable(),
                IconColumn::make('is_active')->boolean(),
            ])
            ->recordActions([EditAction::make()])
            ->defaultSort('name');
    }
}
