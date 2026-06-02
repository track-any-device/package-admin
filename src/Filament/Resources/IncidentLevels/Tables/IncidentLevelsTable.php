<?php

namespace TrackAnyDevice\Admin\Filament\Resources\IncidentLevels\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\ColorColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class IncidentLevelsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('level_number')->sortable(),
                TextColumn::make('label')->searchable()->sortable(),
                ColorColumn::make('color'),
                TextColumn::make('tenant.name')->label('Tenant')->placeholder('Platform default'),
                TextColumn::make('description')->limit(40)->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('tenant_id')
                    ->label('Tenant')
                    ->relationship('tenant', 'name')
                    ->searchable()
                    ->preload(),
            ])
            ->recordActions([ViewAction::make(), EditAction::make()])
            ->toolbarActions([BulkActionGroup::make([DeleteBulkAction::make()])])
            ->defaultSort('level_number');
    }
}
