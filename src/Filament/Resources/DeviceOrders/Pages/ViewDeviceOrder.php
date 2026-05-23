<?php

namespace TrackAnyDevice\Admin\Filament\Resources\DeviceOrders\Pages;

use TrackAnyDevice\Core\Enums\DeviceOrderStatus;
use TrackAnyDevice\Admin\Filament\Resources\DeviceOrders\DeviceOrderResource;
use TrackAnyDevice\Core\Models\DeviceOrder;
use Filament\Actions\Action;
use Filament\Actions\EditAction;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ViewRecord;
use Illuminate\Support\Facades\Auth;

class ViewDeviceOrder extends ViewRecord
{
    protected static string $resource = DeviceOrderResource::class;

    protected function getHeaderActions(): array
    {
        /** @var DeviceOrder $order */
        $order = $this->record;

        return [
            Action::make('confirm')
                ->label('Confirm & Assign')
                ->icon('heroicon-o-check-circle')
                ->color('success')
                ->requiresConfirmation()
                ->visible(fn () => $order->isPending())
                ->action(function () use ($order) {
                    $order->update([
                        'status' => DeviceOrderStatus::Confirmed,
                        'confirmed_by' => Auth::id(),
                        'confirmed_at' => now(),
                    ]);
                    $this->record->refresh();
                    Notification::make()->title('Order confirmed — device assigned to tenant')->success()->send();
                }),

            Action::make('deliver')
                ->label('Mark Delivered')
                ->icon('heroicon-o-truck')
                ->color('info')
                ->requiresConfirmation()
                ->visible(fn () => $order->isConfirmed())
                ->action(function () use ($order) {
                    $order->update([
                        'status' => DeviceOrderStatus::Delivered,
                        'delivered_at' => now(),
                    ]);
                    $this->record->refresh();
                    Notification::make()->title('Order marked as delivered')->success()->send();
                }),

            Action::make('cancel')
                ->label('Cancel Order')
                ->icon('heroicon-o-x-circle')
                ->color('danger')
                ->requiresConfirmation()
                ->visible(fn () => $order->isPending())
                ->action(function () use ($order) {
                    $order->update(['status' => DeviceOrderStatus::Cancelled]);
                    $this->record->refresh();
                    Notification::make()->title('Order cancelled')->warning()->send();
                }),

            EditAction::make(),
        ];
    }
}
