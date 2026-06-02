<?php

namespace TrackAnyDevice\Admin\Filament\Resources\IncidentStatusOptions\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\ColorColumn;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;

class IncidentStatusOptionsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('key')->searchable()->sortable(),
                TextColumn::make('label')->searchable()->sortable(),
                ColorColumn::make('color'),
                IconColumn::make('is_open')->boolean()->label('Open'),
                IconColumn::make('is_closed')->boolean()->label('Closed'),
                TextColumn::make('sort_order')->sortable(),
                TextColumn::make('tenant.name')->label('Tenant')->placeholder('Platform default'),
            ])
            ->filters([
                TernaryFilter::make('is_open'),
                TernaryFilter::make('is_closed'),
                SelectFilter::make('tenant')
                    ->relationship('tenant', 'name'),
            ])
            ->recordActions([ViewAction::make(), EditAction::make()])
            ->toolbarActions([BulkActionGroup::make([DeleteBulkAction::make()])])
            ->defaultSort('sort_order');
    }
}
