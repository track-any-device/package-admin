<?php

namespace TrackAnyDevice\Admin\Filament\Resources\DeviceOrders\Tables;

use TrackAnyDevice\Core\Enums\DeviceOrderStatus;
use TrackAnyDevice\Core\Models\DeviceOrder;
use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Auth;

class DeviceOrdersTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')
                    ->label('Order #')
                    ->sortable(),

                TextColumn::make('tenant.name')
                    ->label('Tenant')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('deviceType.name')
                    ->label('Device Type')
                    ->sortable(),

                TextColumn::make('device.name')
                    ->label('Assigned Device')
                    ->placeholder('— Not assigned —'),

                TextColumn::make('status')
                    ->badge()
                    ->formatStateUsing(fn (DeviceOrderStatus $state) => $state->label())
                    ->color(fn (DeviceOrderStatus $state) => $state->color()),

                TextColumn::make('confirmedBy.name')
                    ->label('Confirmed By')
                    ->placeholder('—')
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('confirmed_at')
                    ->dateTime('d M Y, H:i')
                    ->placeholder('—')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('created_at')
                    ->dateTime('d M Y, H:i')
                    ->label('Ordered At')
                    ->sortable(),
            ])
            ->filters([
                SelectFilter::make('status')->options(
                    collect(DeviceOrderStatus::cases())->mapWithKeys(fn ($s) => [$s->value => $s->label()])
                ),
            ])
            ->recordActions([
                ViewAction::make(),
                EditAction::make(),
                Action::make('confirm')
                    ->label('Confirm & Assign')
                    ->icon('heroicon-o-check-circle')
                    ->color('success')
                    ->requiresConfirmation()
                    ->visible(fn (DeviceOrder $record) => $record->isPending())
                    ->action(function (DeviceOrder $record) {
                        $record->update([
                            'status' => DeviceOrderStatus::Confirmed,
                            'confirmed_by' => Auth::id(),
                            'confirmed_at' => now(),
                        ]);
                    }),
                Action::make('deliver')
                    ->label('Mark Delivered')
                    ->icon('heroicon-o-truck')
                    ->color('info')
                    ->requiresConfirmation()
                    ->visible(fn (DeviceOrder $record) => $record->isConfirmed())
                    ->action(fn (DeviceOrder $record) => $record->update([
                        'status' => DeviceOrderStatus::Delivered,
                        'delivered_at' => now(),
                    ])),
                Action::make('cancel')
                    ->label('Cancel')
                    ->icon('heroicon-o-x-circle')
                    ->color('danger')
                    ->requiresConfirmation()
                    ->visible(fn (DeviceOrder $record) => $record->isPending())
                    ->action(fn (DeviceOrder $record) => $record->update([
                        'status' => DeviceOrderStatus::Cancelled,
                    ])),
            ])
            ->toolbarActions([BulkActionGroup::make([DeleteBulkAction::make()])])
            ->defaultSort('created_at', 'desc');
    }
}
