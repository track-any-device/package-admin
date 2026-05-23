<?php

namespace TrackAnyDevice\Admin\Filament\Resources\ComputeBoards\Schemas;

use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class ComputeBoardForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('Board')->columns(2)->schema([
                TextInput::make('name')->required(),
                TextInput::make('manufacturer')->required(),
                TextInput::make('mcu')->placeholder('STM32 / ESP32 / nRF52'),
                TextInput::make('operating_voltage')->numeric()->step(0.01),
                TextInput::make('flash_kb')->numeric()->label('Flash (KB)'),
                TextInput::make('ram_kb')->numeric()->label('RAM (KB)'),
                TextInput::make('datasheet_url')->url()->nullable()->columnSpanFull(),
                Textarea::make('notes')->rows(2)->columnSpanFull(),
            ]),
        ]);
    }
}
