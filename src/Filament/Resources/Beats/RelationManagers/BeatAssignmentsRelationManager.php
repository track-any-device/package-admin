<?php

namespace TrackAnyDevice\Admin\Filament\Resources\Beats\RelationManagers;

use TrackAnyDevice\Core\Enums\BeatAssignmentStatus;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class BeatAssignmentsRelationManager extends RelationManager
{
    protected static string $relationship = 'beatAssignments';

    public function form(Schema $schema): Schema
    {
        return $schema->components([]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('id')
            ->columns([
                TextColumn::make('device.name')
                    ->label('Device')
                    ->searchable(),

                TextColumn::make('device.imei')
                    ->label('IMEI')
                    ->fontFamily('mono'),

                TextColumn::make('status')
                    ->badge()
                    ->formatStateUsing(fn (BeatAssignmentStatus $state) => $state->label())
                    ->color(fn (BeatAssignmentStatus $state) => $state->color()),

                TextColumn::make('effective_from')
                    ->dateTime('d M Y, H:i')
                    ->label('From'),

                TextColumn::make('effective_to')
                    ->dateTime('d M Y, H:i')
                    ->label('To')
                    ->placeholder('Active'),

                TextColumn::make('assignedBy.name')
                    ->label('By'),
            ])
            ->filters([
                SelectFilter::make('status')->options(
                    collect(BeatAssignmentStatus::cases())->mapWithKeys(fn ($s) => [$s->value => $s->label()])
                ),
            ])
            ->defaultSort('effective_from', 'desc');
    }

    public function isReadOnly(): bool
    {
        return true;
    }
}
