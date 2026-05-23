<?php

namespace TrackAnyDevice\Admin\Filament\Resources\Devices\RelationManagers;

use TrackAnyDevice\Core\Enums\DeviceAssignmentStatus;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class DeviceAssignmentsRelationManager extends RelationManager
{
    protected static string $relationship = 'deviceAssignments';

    public function form(Schema $schema): Schema
    {
        return $schema->components([]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('id')
            ->columns([
                TextColumn::make('assignee.name')
                    ->label('Assignee')
                    ->searchable(),

                TextColumn::make('assignee.assigneeType.name')
                    ->label('Type'),

                TextColumn::make('status')
                    ->badge()
                    ->formatStateUsing(fn (DeviceAssignmentStatus $state) => $state->label())
                    ->color(fn (DeviceAssignmentStatus $state) => $state->color()),

                TextColumn::make('condition_out')
                    ->label('Condition Out'),

                TextColumn::make('condition_in')
                    ->label('Condition In')
                    ->placeholder('—'),

                TextColumn::make('assigned_at')
                    ->dateTime('d M Y, H:i')
                    ->label('Assigned'),

                TextColumn::make('returned_at')
                    ->dateTime('d M Y, H:i')
                    ->label('Returned')
                    ->placeholder('—'),

                TextColumn::make('assignedBy.name')
                    ->label('By'),
            ])
            ->filters([
                SelectFilter::make('status')->options(
                    collect(DeviceAssignmentStatus::cases())->mapWithKeys(fn ($s) => [$s->value => $s->label()])
                ),
            ])
            ->defaultSort('assigned_at', 'desc');
    }

    public function isReadOnly(): bool
    {
        return true;
    }
}
