<?php

namespace TrackAnyDevice\Admin\Filament\Resources\PurchaseOrders\Tables;

use TrackAnyDevice\Core\Enums\PurchaseOrderStatus;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class PurchaseOrdersTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->defaultSort('created_at', 'desc')
            ->columns([
                TextColumn::make('id')
                    ->label('PO #')
                    ->sortable(),

                TextColumn::make('vendor_name')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('deviceType.name')
                    ->label('Device Type')
                    ->searchable(),

                TextColumn::make('warehouse.name')
                    ->label('Warehouse'),

                TextColumn::make('quantity_ordered')
                    ->label('Ordered')
                    ->sortable(),

                TextColumn::make('quantity_received')
                    ->label('Received')
                    ->sortable(),

                TextColumn::make('status')
                    ->badge()
                    ->formatStateUsing(fn (PurchaseOrderStatus $state) => $state->label())
                    ->color(fn (PurchaseOrderStatus $state) => $state->color()),

                TextColumn::make('orderedBy.name')
                    ->label('Ordered By')
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('expected_at')
                    ->label('Expected')
                    ->date('d M Y')
                    ->sortable()
                    ->placeholder('—'),

                TextColumn::make('created_at')
                    ->dateTime('d M Y')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->options(collect(PurchaseOrderStatus::cases())
                        ->mapWithKeys(fn (PurchaseOrderStatus $s) => [$s->value => $s->label()])
                        ->all()),
            ])
            ->recordActions([
                ViewAction::make(),
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
