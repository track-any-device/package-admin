<?php

namespace TrackAnyDevice\Admin\Filament\Resources\ChargingSets\Schemas;

use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class ChargingSetForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('Charger')->columns(2)->schema([
                TextInput::make('name')->required(),
                TextInput::make('connector')->required()->placeholder('USB-A / USB-C / Micro-USB / Pogo-pin'),
                TextInput::make('voltage')->numeric()->required()->step(0.01)->suffix('V'),
                TextInput::make('current_ma')->numeric()->required()->suffix('mA'),
                Toggle::make('wireless')->default(false),
                Textarea::make('notes')->rows(2)->columnSpanFull(),
            ]),
        ]);
    }
}
