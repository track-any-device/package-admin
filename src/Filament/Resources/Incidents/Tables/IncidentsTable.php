<?php

namespace TrackAnyDevice\Admin\Filament\Resources\Incidents\Tables;

use TrackAnyDevice\Core\Enums\AlertRuleEventType;
use TrackAnyDevice\Core\Enums\IncidentPriority;
use TrackAnyDevice\Core\Enums\IncidentStatus;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class IncidentsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('status')
                    ->badge()
                    ->formatStateUsing(fn (IncidentStatus $state) => $state->label())
                    ->color(fn (IncidentStatus $state) => $state->color()),

                TextColumn::make('priority')
                    ->badge()
                    ->formatStateUsing(fn (IncidentPriority $state) => $state->label())
                    ->color(fn (IncidentPriority $state) => $state->color()),

                TextColumn::make('event_type')
                    ->label('Event')
                    ->formatStateUsing(fn (AlertRuleEventType $state) => $state->label()),

                TextColumn::make('device.name')
                    ->label('Device')
                    ->searchable(),

                TextColumn::make('assignee.name')
                    ->label('Assignee')
                    ->placeholder('—'),

                TextColumn::make('beat.name')
                    ->label('Beat')
                    ->placeholder('—'),

                TextColumn::make('triggered_at')
                    ->dateTime('d M Y, H:i')
                    ->label('Triggered')
                    ->sortable(),

                TextColumn::make('acknowledged_at')
                    ->dateTime('d M Y, H:i')
                    ->label('Acknowledged')
                    ->placeholder('—')
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('resolved_at')
                    ->dateTime('d M Y, H:i')
                    ->label('Resolved')
                    ->placeholder('—')
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('status')->options(
                    collect(IncidentStatus::cases())->mapWithKeys(fn ($s) => [$s->value => $s->label()])
                ),
                SelectFilter::make('priority')->options(
                    collect(IncidentPriority::cases())->mapWithKeys(fn ($s) => [$s->value => $s->label()])
                ),
                SelectFilter::make('event_type')->label('Event Type')->options(
                    collect(AlertRuleEventType::cases())->mapWithKeys(fn ($s) => [$s->value => $s->label()])
                ),
            ])
            ->recordActions([ViewAction::make()])
            ->toolbarActions([BulkActionGroup::make([DeleteBulkAction::make()])])
            ->defaultSort('triggered_at', 'desc');
    }
}
