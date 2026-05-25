<?php

namespace TrackAnyDevice\Admin\Filament\Resources\OAuthClients\Tables;

use TrackAnyDevice\Core\Enums\OAuthClientKind;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;

class OAuthClientsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->searchable()
                    ->sortable(),

                BadgeColumn::make('kind')
                    ->colors([
                        'info'    => OAuthClientKind::Tenant->value,
                        'warning' => OAuthClientKind::My->value,
                        'success' => OAuthClientKind::Web->value,
                        'gray'    => OAuthClientKind::Admin->value,
                        'danger'  => OAuthClientKind::GraphQl->value,
                    ])
                    ->formatStateUsing(fn (OAuthClientKind $state): string => $state->label())
                    ->sortable(),

                TextColumn::make('tenant.name')
                    ->label('Tenant')
                    ->placeholder('—')
                    ->searchable()
                    ->sortable()
                    ->toggleable(),

                TextColumn::make('client_id')
                    ->label('Client ID')
                    ->copyable()
                    ->copyMessage('Client ID copied')
                    ->limit(20)
                    ->tooltip(fn ($record) => $record->client_id)
                    ->searchable(),

                IconColumn::make('is_active')
                    ->label('Active')
                    ->boolean()
                    ->trueIcon('heroicon-o-check-circle')
                    ->falseIcon('heroicon-o-x-circle')
                    ->trueColor('success')
                    ->falseColor('danger')
                    ->sortable(),

                TextColumn::make('created_at')
                    ->label('Created')
                    ->since()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('kind')
                    ->options(collect(OAuthClientKind::cases())->mapWithKeys(
                        fn (OAuthClientKind $k) => [$k->value => $k->label()]
                    )->all()),

                TernaryFilter::make('is_active')
                    ->label('Active'),
            ])
            ->recordActions([
                ViewAction::make(),
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('created_at', 'desc');
    }
}
