<?php

namespace TrackAnyDevice\Admin\Filament\Resources\Drivers\Schemas;

use TrackAnyDevice\Core\Enums\StreamChannel;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class DriverForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('Driver')->columns(2)->schema([
                TextInput::make('name')->required()->placeholder('JT808Driver'),
                TextInput::make('class')->required()->unique(ignoreRecord: true)->placeholder('App\\Drivers\\Jt808Driver')->columnSpanFull(),
                Select::make('stream_channel')
                    ->options(collect(StreamChannel::cases())->mapWithKeys(fn ($c) => [$c->value => $c->label()])->all())
                    ->required(),
                TextInput::make('version')->placeholder('1.3'),
                Toggle::make('supports_gsm_commands')->default(true),
                Toggle::make('supports_stream')->default(false),
                Textarea::make('notes')->rows(2)->columnSpanFull(),
            ]),
        ]);
    }
}
