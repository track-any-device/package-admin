<?php

namespace TrackAnyDevice\Admin\Filament\Resources\Sensors\Schemas;

use TrackAnyDevice\Core\Enums\SensorDataType;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Illuminate\Support\Str;

class SensorForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('Sensor')->columns(2)->schema([
                TextInput::make('name')
                    ->required()
                    ->live(onBlur: true)
                    ->afterStateUpdated(fn ($state, callable $set) => $set('slug', Str::slug($state))),
                TextInput::make('slug')->required()->unique(ignoreRecord: true),
                TextInput::make('label')->helperText('Human-readable label (defaults to name).'),
                Select::make('data_type')
                    ->options(collect(SensorDataType::cases())->mapWithKeys(fn ($c) => [$c->value => $c->label()])->all())
                    ->default(SensorDataType::Float->value)
                    ->required(),
                TextInput::make('unit')->placeholder('% / mV / dBm / m / km/h / °C'),
                TextInput::make('icon')->placeholder('Heroicon name (e.g. battery-100)'),
                TextInput::make('sort_order')->numeric()->default(0),
                Textarea::make('description')->rows(2)->columnSpanFull(),
            ]),
        ]);
    }
}
