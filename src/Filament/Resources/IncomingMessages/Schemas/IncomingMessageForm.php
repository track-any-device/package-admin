<?php

namespace TrackAnyDevice\Admin\Filament\Resources\IncomingMessages\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class IncomingMessageForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Message')
                    ->columns(2)
                    ->schema([
                        TextInput::make('sender_number')
                            ->label('Sender Number')
                            ->disabled(),

                        Select::make('source')
                            ->options([
                                'gateway_api' => 'Gateway API',
                                'twilio' => 'Twilio',
                                'manual' => 'Manual',
                            ])
                            ->disabled(),

                        DateTimePicker::make('received_at')
                            ->label('Received At')
                            ->disabled(),

                        DateTimePicker::make('processed_at')
                            ->label('Processed At')
                            ->disabled(),

                        Textarea::make('raw_message')
                            ->label('Raw Message')
                            ->rows(4)
                            ->disabled()
                            ->columnSpanFull(),

                        Textarea::make('processing_error')
                            ->label('Processing Error')
                            ->rows(3)
                            ->disabled()
                            ->columnSpanFull()
                            ->visible(fn ($record) => $record?->processing_error !== null),
                    ]),
            ]);
    }
}
