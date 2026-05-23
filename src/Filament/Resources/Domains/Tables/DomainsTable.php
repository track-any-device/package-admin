<?php

namespace TrackAnyDevice\Admin\Filament\Resources\Domains\Tables;

use TrackAnyDevice\Core\Models\Tenant;
use Filament\Actions\Action;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Notifications\Notification;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;

class DomainsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('domain')
                    ->label('Hostname')
                    ->copyable()
                    ->searchable()
                    ->sortable()
                    ->url(fn ($record) => 'https://'.$record->domain, true),

                TextColumn::make('tenant.name')
                    ->label('Tenant')
                    ->searchable()
                    ->sortable()
                    ->url(fn ($record) => $record->tenant_id
                        ? route('filament.admin.resources.organisations.view', ['record' => $record->tenant_id])
                        : null),

                TextColumn::make('tenant.slug')
                    ->label('Slug')
                    ->badge()
                    ->toggleable(isToggledHiddenByDefault: true),

                IconColumn::make('is_primary')
                    ->label('Primary')
                    ->boolean()
                    ->trueIcon('heroicon-o-star')
                    ->falseIcon('heroicon-o-minus')
                    ->trueColor('warning')
                    ->sortable(),

                TextColumn::make('created_at')
                    ->label('Added')
                    ->dateTime('d M Y')
                    ->sortable()
                    ->toggleable(),
            ])
            ->filters([
                SelectFilter::make('tenant_id')
                    ->label('Tenant')
                    ->relationship('tenant', 'name')
                    ->getOptionLabelFromRecordUsing(fn (Tenant $record) => $record->name.' ('.$record->slug.')')
                    ->searchable()
                    ->preload(),

                TernaryFilter::make('is_primary')
                    ->label('Primary')
                    ->placeholder('All domains')
                    ->trueLabel('Primary only')
                    ->falseLabel('Non-primary only'),
            ])
            ->recordActions([
                Action::make('set_primary')
                    ->label('Set Primary')
                    ->icon('heroicon-o-star')
                    ->color('warning')
                    ->visible(fn ($record) => ! $record->is_primary)
                    ->requiresConfirmation()
                    ->modalHeading('Set as primary domain')
                    ->modalDescription(fn ($record) => "This will demote any other primary domain for {$record->tenant?->name} and promote {$record->domain} to primary.")
                    ->action(function ($record) {
                        $record->tenant?->domains()->update(['is_primary' => false]);
                        $record->update(['is_primary' => true]);
                        Notification::make()->title('Primary domain updated')->success()->send();
                    }),
                ViewAction::make(),
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->defaultSort('created_at', 'desc');
    }
}
