<?php

namespace TrackAnyDevice\Admin\Filament\Resources\DeviceCommands\Schemas;

use TrackAnyDevice\Core\Enums\DeviceCommandStatus;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class DeviceCommandForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Command')
                    ->columns(2)
                    ->schema([
                        Select::make('device_id')
                            ->label('Device')
                            ->relationship('device', 'name')
                            ->disabled(),

                        TextInput::make('command_type')
                            ->label('Command Type')
                            ->disabled(),

                        Select::make('channel')
                            ->options([
                                'sms' => 'SMS',
                                'tcp' => 'TCP',
                                'http' => 'HTTP',
                            ])
                            ->disabled(),

                        Select::make('status')
                            ->options(collect(DeviceCommandStatus::cases())->mapWithKeys(
                                fn (DeviceCommandStatus $s) => [$s->value => $s->label()]
                            ))
                            ->disabled(),

                        Textarea::make('command_payload')
                            ->label('Command Payload')
                            ->rows(2)
                            ->disabled()
                            ->columnSpanFull(),
                    ]),

                Section::make('Delivery')
                    ->columns(2)
                    ->schema([
                        Select::make('requested_by')
                            ->label('Requested By')
                            ->relationship('requestedBy', 'name')
                            ->disabled(),

                        DateTimePicker::make('sent_at')
                            ->label('Sent At')
                            ->disabled(),

                        Textarea::make('response')
                            ->label('Device Response')
                            ->rows(3)
                            ->disabled()
                            ->columnSpanFull()
                            ->visible(fn ($record) => $record?->response !== null),

                        Textarea::make('failed_reason')
                            ->label('Failure Reason')
                            ->rows(3)
                            ->disabled()
                            ->columnSpanFull()
                            ->visible(fn ($record) => $record?->failed_reason !== null),
                    ]),
            ]);
    }
}
