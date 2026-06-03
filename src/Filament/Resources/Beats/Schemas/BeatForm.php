<?php

namespace TrackAnyDevice\Admin\Filament\Resources\Beats\Schemas;

use TrackAnyDevice\Core\Enums\BeatStatus;
use TrackAnyDevice\Core\Enums\BeatZoneType;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class BeatForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Beat Details')
                    ->columns(2)
                    ->schema([
                        TextInput::make('name')->required()->maxLength(150)->columnSpanFull(),
                        Textarea::make('description')->rows(2)->columnSpanFull(),
                        Select::make('supervisor_id')
                            ->label('Supervisor (Assignee)')
                            ->relationship(name: 'supervisor', titleAttribute: 'name')
                            ->helperText('Promote one of this tenant\'s assignees as the field-side leader of this beat.')
                            ->searchable()->preload()->nullable()->columnSpan(1),
                        Select::make('zone_type')
                            ->label('Zone Type')
                            ->options(collect(BeatZoneType::cases())->mapWithKeys(fn (BeatZoneType $z) => [$z->value => $z->label()]))
                            ->required()
                            ->default(BeatZoneType::Inclusion->value)
                            ->helperText(fn ($state) => BeatZoneType::tryFrom($state ?? 'inclusion')?->description())
                            ->columnSpan(1),
                        Select::make('status')
                            ->options(collect(BeatStatus::cases())->mapWithKeys(fn (BeatStatus $s) => [$s->value => $s->label()]))
                            ->required()->default(BeatStatus::Active->value)->columnSpan(1),
                    ]),
                Section::make('Geo-fence')
                    ->schema([
                        Textarea::make('coordinates')
                            ->label('Coordinates (JSON)')
                            ->rows(6)
                            ->helperText('Polygon: [[lat,lng],...] — Circle: {"lat":…,"lng":…,"radius":500}')
                            ->columnSpanFull()
                            ->dehydrateStateUsing(fn ($state) => is_string($state) ? json_decode($state, true) : $state)
                            ->formatStateUsing(fn ($state) => is_array($state) ? json_encode($state, JSON_PRETTY_PRINT) : $state),
                    ]),
            ]);
    }
}
