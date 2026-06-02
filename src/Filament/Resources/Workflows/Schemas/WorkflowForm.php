<?php

namespace TrackAnyDevice\Admin\Filament\Resources\Workflows\Schemas;

use TrackAnyDevice\Core\Enums\WorkflowTriggerType;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class WorkflowForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Workflow')
                    ->columns(2)
                    ->schema([
                        TextInput::make('name')
                            ->required()
                            ->maxLength(150),
                        Select::make('trigger_type')
                            ->options(
                                collect(WorkflowTriggerType::cases())
                                    ->mapWithKeys(fn ($t) => [$t->value => $t->label()])
                            )
                            ->required(),
                        Toggle::make('is_enabled')
                            ->default(true),
                        Textarea::make('description')
                            ->rows(2)
                            ->columnSpanFull(),
                    ]),
                Section::make('Ownership')
                    ->columns(2)
                    ->schema([
                        Select::make('tenant_id')
                            ->relationship(name: 'tenant', titleAttribute: 'name')
                            ->searchable()
                            ->placeholder('—'),
                        Select::make('user_id')
                            ->label('Owner User')
                            ->relationship(name: 'user', titleAttribute: 'name')
                            ->searchable()
                            ->placeholder('—'),
                    ]),
            ]);
    }
}
