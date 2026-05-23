<?php

namespace TrackAnyDevice\Admin\Filament\Resources\DeviceCommands\Pages;

use TrackAnyDevice\Core\Enums\DeviceCommandStatus;
use TrackAnyDevice\Admin\Filament\Resources\DeviceCommands\DeviceCommandResource;
use TrackAnyDevice\Core\Jobs\QueueDeviceCommand;
use TrackAnyDevice\Core\Models\DeviceCommand;
use TrackAnyDevice\Core\Services\DeviceCommandService;
use Filament\Actions\Action;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ViewRecord;

class ViewDeviceCommand extends ViewRecord
{
    protected static string $resource = DeviceCommandResource::class;

    protected function getHeaderActions(): array
    {
        /** @var DeviceCommand $command */
        $command = $this->record;

        return [
            Action::make('cancel')
                ->label('Cancel Command')
                ->icon('heroicon-o-x-circle')
                ->color('danger')
                ->requiresConfirmation()
                ->visible(fn () => ! $command->isTerminal())
                ->action(function () use ($command) {
                    app(DeviceCommandService::class)->cancel($command);
                    $this->record->refresh();
                    Notification::make()->title('Command cancelled')->success()->send();
                }),

            Action::make('retry')
                ->label('Retry')
                ->icon('heroicon-o-arrow-path')
                ->color('warning')
                ->requiresConfirmation()
                ->visible(fn () => $command->status === DeviceCommandStatus::Failed)
                ->action(function () use ($command) {
                    $command->update([
                        'status' => DeviceCommandStatus::Pending,
                        'failed_reason' => null,
                    ]);
                    QueueDeviceCommand::dispatch($command->id);
                    $this->record->refresh();
                    Notification::make()->title('Command requeued for retry')->success()->send();
                }),
        ];
    }
}
