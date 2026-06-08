<?php

namespace TrackAnyDevice\Admin\Filament\Resources\DeviceOrders\Tables;

use TrackAnyDevice\Core\Enums\DeviceOrderStatus;
use TrackAnyDevice\Core\Enums\PaymentMethod;
use TrackAnyDevice\Core\Models\DeviceOrder;
use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Forms\Components\Select;
use Filament\Notifications\Notification;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Auth;
use TrackAnyDevice\Core\Models\Device;

class DeviceOrdersTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')
                    ->label('Order #')
                    ->formatStateUsing(fn (DeviceOrder $record) => $record->referenceNumber())
                    ->sortable(),

                TextColumn::make('user.name')
                    ->label('Customer')
                    ->searchable()
                    ->placeholder('—'),

                TextColumn::make('product.name')
                    ->label('Product')
                    ->searchable()
                    ->placeholder('— Legacy —'),

                TextColumn::make('tenant.name')
                    ->label('Tenant')
                    ->searchable()
                    ->sortable()
                    ->placeholder('— Direct —')
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('quantity')
                    ->sortable(),

                TextColumn::make('total_amount')
                    ->label('Total')
                    ->money(fn (DeviceOrder $record) => $record->currency ?? 'PKR')
                    ->sortable()
                    ->placeholder('—'),

                TextColumn::make('payment_method')
                    ->label('Payment')
                    ->formatStateUsing(fn (?PaymentMethod $state) => $state?->label() ?? '—')
                    ->badge()
                    ->color(fn (?PaymentMethod $state) => $state?->color() ?? 'gray'),

                TextColumn::make('device.name')
                    ->label('Assigned Device')
                    ->placeholder('— Not assigned —'),

                TextColumn::make('claim_code')
                    ->label('Claim Code')
                    ->copyable()
                    ->placeholder('—')
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('status')
                    ->badge()
                    ->formatStateUsing(fn (DeviceOrderStatus $state) => $state->label())
                    ->color(fn (DeviceOrderStatus $state) => $state->color()),

                TextColumn::make('shipping_name')
                    ->label('Ship To')
                    ->placeholder('—')
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
                SelectFilter::make('payment_method')->options(
                    collect(PaymentMethod::cases())->mapWithKeys(fn ($p) => [$p->value => $p->label()])
                ),
            ])
            ->recordActions([
                ViewAction::make(),
                EditAction::make(),
                Action::make('assign_device')
                    ->label('Assign Device')
                    ->icon('heroicon-o-cpu-chip')
                    ->color('primary')
                    ->visible(fn (DeviceOrder $record) => $record->isPending() && ! $record->device_id)
                    ->form([
                        Select::make('device_id')
                            ->label('Stock Device')
                            ->options(fn () => Device::whereNull('tenant_id')->pluck('name', 'id')->all())
                            ->searchable()
                            ->required(),
                    ])
                    ->action(function (DeviceOrder $record, array $data) {
                        $record->update(['device_id' => $data['device_id']]);
                        Notification::make()->title('Device assigned to order.')->success()->send();
                    }),
                Action::make('confirm')
                    ->label('Confirm')
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
                    ->action(function (DeviceOrder $record) {
                        $record->update([
                            'status' => DeviceOrderStatus::Delivered,
                            'delivered_at' => now(),
                        ]);

                        if ($record->claim_code) {
                            Notification::make()
                                ->title('Order delivered — Claim code: ' . $record->claim_code)
                                ->body('Include this code in the delivery packaging so the customer can claim their device.')
                                ->success()
                                ->persistent()
                                ->send();
                        }
                    }),
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
