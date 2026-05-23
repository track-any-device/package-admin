<?php

namespace TrackAnyDevice\Admin\Filament\Resources\BeatTemplates\Tables;

use TrackAnyDevice\Core\Enums\GeoFenceType;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class BeatTemplatesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')->searchable()->sortable(),
                TextColumn::make('geo_fence_type')
                    ->label('Type')
                    ->badge()
                    ->formatStateUsing(fn (GeoFenceType $state) => $state->label())
                    ->color('info'),
                TextColumn::make('beats_count')
                    ->counts('beats')
                    ->label('Beats using')
                    ->sortable(),
                TextColumn::make('version')->sortable(),
                TextColumn::make('creator.name')
                    ->label('Created by')
                    ->placeholder('—')
                    ->toggleable(),
                IconColumn::make('is_active')->boolean()->label('Active'),
                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('geo_fence_type')
                    ->label('Type')
                    ->options(
                        collect(GeoFenceType::cases())
                            ->mapWithKeys(fn (GeoFenceType $t) => [$t->value => $t->label()])
                    ),
            ])
            ->recordActions([EditAction::make()])
            ->toolbarActions([BulkActionGroup::make([DeleteBulkAction::make()])])
            ->defaultSort('name');
    }
}
