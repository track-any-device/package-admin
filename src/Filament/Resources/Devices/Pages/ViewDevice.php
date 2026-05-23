<?php

namespace TrackAnyDevice\Admin\Filament\Resources\Devices\Pages;

use TrackAnyDevice\Core\Enums\DeviceCommandStatus;
use TrackAnyDevice\Admin\Filament\Resources\Devices\DeviceResource;
use TrackAnyDevice\Core\Models\DeviceCommand;
use TrackAnyDevice\Core\Providers\DeviceServiceProvider;
use Filament\Actions\Action;
use Filament\Actions\EditAction;
use Filament\Forms\Components\KeyValue;
use Filament\Forms\Components\Select;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ViewRecord;

class ViewDevice extends ViewRecord
{
    protected static string $resource = DeviceResource::class;

    protected function getHeaderActions(): array
    {
        return [
            $this->sendCommandAction(),
            EditAction::make(),
        ];
    }

    private function sendCommandAction(): Action
    {
        return Action::make('sendCommand')
            ->label('Send Command')
            ->icon('heroicon-o-command-line')
            ->color('warning')
            ->form(function () {
                $device = $this->record;
                $device->load('deviceType');

                try {
                    $driver = DeviceServiceProvider::driverFor($device->deviceType->slug);
                    $commands = $driver->supportedCommands();
                } catch (\Throwable) {
                    $commands = [];
                }

                $options = collect($commands)
                    ->mapWithKeys(fn ($label, $key) => [$key => "{$key} — {$label}"])
                    ->all();

                return [
                    Select::make('command_type')
                        ->label('Command')
                        ->options($options)
                        ->required()
                        ->searchable(),

                    KeyValue::make('params')
                        ->label('Parameters (optional)')
                        ->helperText('Key-value pairs for command params. Password is injected automatically from the device record.')
                        ->addActionLabel('Add parameter')
                        ->reorderable(false),
                ];
            })
            ->action(function (array $data) {
                $device = $this->record;

                DeviceCommand::create([
                    'device_id' => $device->id,
                    'command_type' => $data['command_type'],
                    'command_payload' => $data['params'] ?? [],
                    'channel' => 'sms',
                    'status' => DeviceCommandStatus::Pending,
                    'requested_by' => auth()->id(),
                ]);

                Notification::make()
                    ->title('Command queued')
                    ->body("'{$data['command_type']}' has been queued for dispatch.")
                    ->success()
                    ->send();
            });
    }
}
