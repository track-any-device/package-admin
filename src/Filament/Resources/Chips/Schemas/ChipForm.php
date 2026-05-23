<?php

namespace TrackAnyDevice\Admin\Filament\Resources\Chips\Schemas;

use TrackAnyDevice\Core\Enums\ChipType;
use TrackAnyDevice\Core\Models\Sensor;
use Filament\Forms\Components\CheckboxList;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class ChipForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('Chip')->columns(2)->schema([
                TextInput::make('name')->required(),
                TextInput::make('manufacturer')->required(),
                Select::make('type')
                    ->options(collect(ChipType::cases())->mapWithKeys(fn ($c) => [$c->value => $c->label()])->all())
                    ->required(),
                TextInput::make('datasheet_url')->url()->nullable(),
                CheckboxList::make('sensors')
                    ->relationship('sensors', 'name')
                    ->options(Sensor::pluck('name', 'id'))
                    ->columns(3)
                    ->columnSpanFull(),
                Textarea::make('notes')->rows(2)->columnSpanFull(),
            ]),
        ]);
    }
}
