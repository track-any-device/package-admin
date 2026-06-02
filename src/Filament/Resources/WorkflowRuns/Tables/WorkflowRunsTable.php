<?php

namespace TrackAnyDevice\Admin\Filament\Resources\WorkflowRuns\Tables;

use TrackAnyDevice\Core\Enums\WorkflowRunStatus;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class WorkflowRunsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('workflow.name')
                    ->label('Workflow')
                    ->searchable(),

                TextColumn::make('status')
                    ->badge()
                    ->formatStateUsing(fn (WorkflowRunStatus $state) => $state->label())
                    ->color(fn (WorkflowRunStatus $state) => $state->color()),

                TextColumn::make('tenant.name')
                    ->label('Tenant')
                    ->placeholder('—'),

                TextColumn::make('incident_id')
                    ->label('Incident')
                    ->placeholder('—'),

                TextColumn::make('started_at')
                    ->dateTime()
                    ->label('Started')
                    ->sortable(),

                TextColumn::make('completed_at')
                    ->dateTime()
                    ->label('Completed')
                    ->placeholder('—'),

                TextColumn::make('duration_ms')
                    ->label('Duration (ms)')
                    ->numeric()
                    ->placeholder('—'),

                TextColumn::make('error')
                    ->limit(40)
                    ->placeholder('—')
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->options(
                        collect(WorkflowRunStatus::cases())
                            ->mapWithKeys(fn (WorkflowRunStatus $s) => [$s->value => $s->label()])
                    ),
                SelectFilter::make('workflow')
                    ->relationship('workflow', 'name'),
            ])
            ->recordActions([ViewAction::make()])
            ->defaultSort('started_at', 'desc');
    }
}
