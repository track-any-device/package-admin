<?php

namespace TrackAnyDevice\Admin\Filament\Resources\Incidents\Schemas;

use TrackAnyDevice\Core\Enums\AlertRuleEventType;
use TrackAnyDevice\Core\Enums\IncidentPriority;
use TrackAnyDevice\Core\Enums\IncidentStatus;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class IncidentForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Incident')
                    ->columns(2)
                    ->schema([
                        Select::make('status')
                            ->options(collect(IncidentStatus::cases())->mapWithKeys(fn ($s) => [$s->value => $s->label()]))
                            ->required(),
                        Select::make('priority')
                            ->options(collect(IncidentPriority::cases())->mapWithKeys(fn ($s) => [$s->value => $s->label()]))
                            ->required(),
                        Select::make('event_type')
                            ->label('Event Type')
                            ->options(collect(AlertRuleEventType::cases())->mapWithKeys(fn ($s) => [$s->value => $s->label()]))
                            ->required(),
                        TextInput::make('level')
                            ->label('Escalation Level')
                            ->numeric()
                            ->minValue(1)
                            ->helperText('Beat hierarchy violation depth (1 = leaf, 2 = parent, 3+ = ancestor).'),
                        Textarea::make('resolution_notes')->rows(2)->columnSpanFull(),
                    ]),
            ]);
    }
}
