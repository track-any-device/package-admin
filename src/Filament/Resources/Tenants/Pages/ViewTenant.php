<?php

namespace TrackAnyDevice\Admin\Filament\Resources\Tenants\Pages;

use TrackAnyDevice\Admin\Filament\Resources\Tenants\TenantResource;
use TrackAnyDevice\Core\Models\Tenant;
use TrackAnyDevice\Core\Models\TenantStatus;
use Filament\Actions\Action;
use Filament\Actions\EditAction;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ViewRecord;

class ViewTenant extends ViewRecord
{
    protected static string $resource = TenantResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Action::make('approve')
                ->label('Approve Organisation')
                ->icon('heroicon-o-check-circle')
                ->color('success')
                ->requiresConfirmation()
                ->modalHeading('Approve Organisation')
                ->modalDescription(fn () => "Approving \"{$this->getRecord()->name}\" opens its subdomain.")
                ->visible(fn (): bool => $this->getRecord()->status !== TenantStatus::Approved)
                ->action(function (): void {
                    /** @var Tenant $record */
                    $record = $this->getRecord();
                    $record->update([
                        'status' => TenantStatus::Approved,
                        'approved_at' => $record->approved_at ?? now(),
                    ]);
                    Notification::make()
                        ->title("Organisation \"{$record->name}\" approved.")
                        ->success()
                        ->send();
                    $this->refreshFormData(['status', 'approved_at']);
                }),

            Action::make('reject')
                ->label('Reject')
                ->icon('heroicon-o-x-circle')
                ->color('danger')
                ->requiresConfirmation()
                ->visible(fn (): bool => $this->getRecord()->status === TenantStatus::Pending)
                ->action(function (): void {
                    /** @var Tenant $record */
                    $record = $this->getRecord();
                    $record->update(['status' => TenantStatus::Rejected]);
                    Notification::make()
                        ->title("\"{$record->name}\" rejected")
                        ->warning()
                        ->send();
                    $this->refreshFormData(['status']);
                }),

            EditAction::make(),
        ];
    }
}
