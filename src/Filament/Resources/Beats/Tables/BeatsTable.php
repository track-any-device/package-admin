<?php

namespace TrackAnyDevice\Admin\Filament\Resources\Beats\Tables;

use TrackAnyDevice\Core\Enums\BeatStatus;
use TrackAnyDevice\Core\Enums\GeoFenceType;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class BeatsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')->searchable()->sortable(),
                TextColumn::make('geo_fence_type')
                    ->label('Type')->badge()
                    ->formatStateUsing(fn (GeoFenceType $state) => $state->label())
                    ->color('info'),
                TextColumn::make('supervisor.name')->label('Supervisor')->placeholder('—'),
                TextColumn::make('status')
                    ->badge()
                    ->formatStateUsing(fn (BeatStatus $state) => $state->label())
                    ->color(fn (BeatStatus $state) => $state->color()),
                TextColumn::make('beat_assignments_count')->counts('beatAssignments')->label('Assignments')->sortable(),
                TextColumn::make('updated_at')->dateTime()->sortable()->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('status')->options(
                    collect(BeatStatus::cases())->mapWithKeys(fn (BeatStatus $s) => [$s->value => $s->label()])
                ),
                SelectFilter::make('geo_fence_type')->label('Geo-fence Type')->options(
                    collect(GeoFenceType::cases())->mapWithKeys(fn (GeoFenceType $t) => [$t->value => $t->label()])
                ),
            ])
            ->recordActions([ViewAction::make(), EditAction::make()])
            ->toolbarActions([BulkActionGroup::make([DeleteBulkAction::make()])])
            ->defaultSort('name');
    }
}
