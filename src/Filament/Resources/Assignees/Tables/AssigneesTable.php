<?php

namespace TrackAnyDevice\Admin\Filament\Resources\Assignees\Tables;

use TrackAnyDevice\Core\Enums\AssigneeStatus;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class AssigneesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('code')->searchable()->copyable()->fontFamily('mono')->sortable(),
                TextColumn::make('name')->searchable()->sortable(),
                TextColumn::make('assigneeType.name')->label('Type')->sortable(),
                TextColumn::make('status')
                    ->badge()
                    ->formatStateUsing(fn (AssigneeStatus $state) => $state->label())
                    ->color(fn (AssigneeStatus $state) => $state->color()),
                TextColumn::make('updated_at')->dateTime()->sortable()->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('status')->options(
                    collect(AssigneeStatus::cases())->mapWithKeys(fn (AssigneeStatus $s) => [$s->value => $s->label()])
                ),
                SelectFilter::make('assignee_type_id')->label('Type')->relationship('assigneeType', 'name'),
            ])
            ->recordActions([ViewAction::make(), EditAction::make()])
            ->toolbarActions([BulkActionGroup::make([DeleteBulkAction::make()])])
            ->defaultSort('name');
    }
}
