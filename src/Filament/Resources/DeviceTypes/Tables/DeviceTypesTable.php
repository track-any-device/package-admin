<?php

namespace TrackAnyDevice\Admin\Filament\Resources\DeviceTypes\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;

class DeviceTypesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make('image')
                    ->label('')
                    ->width(48)
                    ->height(36)
                    ->defaultImageUrl(fn ($record) => $record->image ? null : asset('images/device-placeholder.png'))
                    ->extraImgAttributes(['class' => 'rounded object-cover']),

                TextColumn::make('name')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('slug')
                    ->searchable()
                    ->copyable()
                    ->color('gray'),

                TextColumn::make('driver_class')
                    ->searchable()
                    ->copyable()
                    ->color('gray')
                    ->limit(40),

                TextColumn::make('price_usd')
                    ->label('USD')
                    ->money('USD')
                    ->sortable(),

                TextColumn::make('price_pkr')
                    ->label('PKR')
                    ->money('PKR')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('quantity')
                    ->label('Stock')
                    ->numeric()
                    ->sortable(),

                IconColumn::make('is_featured')
                    ->boolean()
                    ->label('Featured'),

                IconColumn::make('is_active')
                    ->boolean()
                    ->label('Active'),

                TextColumn::make('devices_count')
                    ->counts('devices')
                    ->label('Devices')
                    ->sortable(),

                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                TernaryFilter::make('is_active')->label('Active'),
                TernaryFilter::make('is_featured')->label('Featured'),
            ])
            ->recordActions([
                ViewAction::make(),
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('name');
    }
}
