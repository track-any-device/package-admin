<?php

namespace TrackAnyDevice\Admin\Filament\Resources\BeatTemplates\Schemas;

use TrackAnyDevice\Core\Enums\GeoFenceType;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class BeatTemplateForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Template')
                    ->columns(2)
                    ->schema([
                        TextInput::make('name')
                            ->required()
                            ->maxLength(150)
                            ->columnSpanFull(),
                        Textarea::make('description')
                            ->rows(2)
                            ->columnSpanFull(),
                        Select::make('geo_fence_type')
                            ->options(
                                collect(GeoFenceType::cases())
                                    ->mapWithKeys(fn (GeoFenceType $t) => [$t->value => $t->label()])
                            )
                            ->required()
                            ->default(GeoFenceType::Polygon->value)
                            ->columnSpan(1),
                        Toggle::make('is_active')
                            ->default(true)
                            ->helperText('Only active templates appear in the user-facing dropdown.')
                            ->columnSpan(1),
                    ]),
                Section::make('Geo-fence shape')
                    ->description('Editing the coordinates here re-syncs every beat linked to this template on save.')
                    ->schema([
                        Textarea::make('coordinates')
                            ->label('Coordinates (JSON)')
                            ->rows(8)
                            ->required()
                            ->helperText('Polygon: [{"lat":…,"lng":…},…] — Circle: {"lat":…,"lng":…,"radius":500}')
                            ->columnSpanFull()
                            ->dehydrateStateUsing(fn ($state) => is_string($state) ? json_decode($state, true) : $state)
                            ->formatStateUsing(fn ($state) => is_array($state) ? json_encode($state, JSON_PRETTY_PRINT) : $state),
                    ]),
            ]);
    }
}
