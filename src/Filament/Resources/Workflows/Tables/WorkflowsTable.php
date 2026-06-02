<?php

namespace TrackAnyDevice\Admin\Filament\Resources\Workflows\Tables;

use TrackAnyDevice\Core\Enums\WorkflowTriggerType;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;

class WorkflowsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('trigger_type')
                    ->badge()
                    ->formatStateUsing(fn (WorkflowTriggerType $state) => $state->label()),

                IconColumn::make('is_enabled')
                    ->boolean()
                    ->label('Enabled'),

                TextColumn::make('tenant.name')
                    ->label('Tenant')
                    ->placeholder('—'),

                TextColumn::make('runs_count')
                    ->counts('runs')
                    ->label('Runs')
                    ->sortable(),

                TextColumn::make('last_run_at')
                    ->dateTime()
                    ->label('Last Run')
                    ->placeholder('Never'),

                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                TernaryFilter::make('is_enabled'),
                SelectFilter::make('tenant')
                    ->relationship('tenant', 'name'),
            ])
            ->recordActions([ViewAction::make(), EditAction::make()])
            ->toolbarActions([BulkActionGroup::make([DeleteBulkAction::make()])])
            ->defaultSort('created_at', 'desc');
    }
}
