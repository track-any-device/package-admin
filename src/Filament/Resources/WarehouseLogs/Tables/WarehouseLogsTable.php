<?php

namespace TrackAnyDevice\Admin\Filament\Resources\WarehouseLogs\Tables;

use TrackAnyDevice\Core\Enums\WarehouseLogDirection;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class WarehouseLogsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->defaultSort('created_at', 'desc')
            ->columns([
                TextColumn::make('device.imei')
                    ->label('Device IMEI')
                    ->searchable()
                    ->sortable()
                    ->copyable(),

                TextColumn::make('device.name')
                    ->label('Device')
                    ->searchable()
                    ->placeholder('—'),

                TextColumn::make('warehouse.name')
                    ->label('Warehouse')
                    ->sortable(),

                TextColumn::make('direction')
                    ->badge()
                    ->formatStateUsing(fn (WarehouseLogDirection $state) => $state->label())
                    ->color(fn (WarehouseLogDirection $state) => $state->color()),

                TextColumn::make('user.name')
                    ->label('By')
                    ->sortable(),

                TextColumn::make('notes')
                    ->limit(50)
                    ->placeholder('—')
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('created_at')
                    ->label('Date')
                    ->dateTime('d M Y, H:i')
                    ->sortable(),
            ])
            ->filters([
                SelectFilter::make('direction')
                    ->options(collect(WarehouseLogDirection::cases())
                        ->mapWithKeys(fn (WarehouseLogDirection $d) => [$d->value => $d->label()])
                        ->all()),

                SelectFilter::make('warehouse_id')
                    ->relationship('warehouse', 'name')
                    ->label('Warehouse')
                    ->preload(),
            ])
            ->recordActions([
                ViewAction::make(),
            ]);
    }
}
