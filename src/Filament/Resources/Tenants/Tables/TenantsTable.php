<?php

namespace TrackAnyDevice\Admin\Filament\Resources\Tenants\Tables;

use TrackAnyDevice\Core\Models\Tenant;
use TrackAnyDevice\Core\Models\TenantStatus;
use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Notifications\Notification;
use Filament\Tables\Columns\ColorColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class TenantsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('slug')
                    ->copyable()
                    ->color('gray')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('app_name')
                    ->label('App Name')
                    ->placeholder('(default)')
                    ->toggleable(isToggledHiddenByDefault: true),

                ColorColumn::make('primary_color')
                    ->label('Brand Colour')
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('color_scheme')
                    ->label('Theme')
                    ->badge()
                    ->color('gray')
                    ->formatStateUsing(fn (?string $state) => ucfirst($state ?? 'default'))
                    ->toggleable(),

                TextColumn::make('type')
                    ->badge()
                    ->color('info'),

                TextColumn::make('interface_mode')
                    ->label('Interface')
                    ->badge()
                    ->color(fn (?string $state) => $state === 'no_org' ? 'warning' : 'gray')
                    ->formatStateUsing(fn (?string $state) => match ($state) {
                        'no_org' => 'No Org',
                        default => 'Default',
                    })
                    ->toggleable(),

                TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->formatStateUsing(fn (TenantStatus $state) => $state->label())
                    ->color(fn (TenantStatus $state) => $state->color())
                    ->sortable(),

                TextColumn::make('approved_at')
                    ->label('Approved')
                    ->dateTime()
                    ->placeholder('—')
                    ->toggleable(),

                TextColumn::make('domains_count')
                    ->label('Domains')
                    ->counts('domains')
                    ->sortable(),

                TextColumn::make('users_count')
                    ->label('Users')
                    ->counts('users')
                    ->sortable(),

                TextColumn::make('devices_count')
                    ->label('Devices')
                    ->counts('devices')
                    ->sortable(),

                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->options(collect(TenantStatus::cases())->mapWithKeys(
                        fn (TenantStatus $s) => [$s->value => $s->label()]
                    )->all()),
            ])
            ->recordActions([
                Action::make('approve')
                    ->label('Approve')
                    ->icon('heroicon-o-check-circle')
                    ->color('success')
                    ->requiresConfirmation()
                    ->modalHeading('Approve Organisation')
                    ->modalDescription(fn (Tenant $record) => "Approving \"{$record->name}\" opens its subdomain.")
                    ->visible(fn (Tenant $record) => $record->status !== TenantStatus::Approved)
                    ->action(function (Tenant $record) {
                        $wasApproved = $record->status === TenantStatus::Approved;
                        $record->update([
                            'status' => TenantStatus::Approved,
                            'approved_at' => $record->approved_at ?? now(),
                        ]);

                        Notification::make()
                            ->title($wasApproved
                                ? "\"{$record->name}\" already approved"
                                : "Organisation \"{$record->name}\" approved.")
                            ->success()
                            ->send();
                    }),
                Action::make('reject')
                    ->label('Reject')
                    ->icon('heroicon-o-x-circle')
                    ->color('danger')
                    ->requiresConfirmation()
                    ->visible(fn (Tenant $record) => $record->status === TenantStatus::Pending)
                    ->action(function (Tenant $record) {
                        $record->update(['status' => TenantStatus::Rejected]);
                        Notification::make()
                            ->title("\"{$record->name}\" rejected")
                            ->warning()
                            ->send();
                    }),
                ViewAction::make(),
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
