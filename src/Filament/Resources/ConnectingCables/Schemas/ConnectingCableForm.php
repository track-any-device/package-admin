<?php

namespace TrackAnyDevice\Admin\Filament\Resources\ConnectingCables\Schemas;

use TrackAnyDevice\Core\Enums\CableProtocol;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TagsInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class ConnectingCableForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('Cable')->columns(2)->schema([
                TextInput::make('name')->required(),
                TextInput::make('connector_type')->required()->placeholder('USB-A / USB-C / Micro-USB'),
                Select::make('protocol')
                    ->options(collect(CableProtocol::cases())->mapWithKeys(fn ($c) => [$c->value => $c->label()])->all())
                    ->required(),
                TagsInput::make('baud_rates')->placeholder('9600, 115200'),
                Textarea::make('notes')->rows(2)->columnSpanFull(),
            ]),
        ]);
    }
}
