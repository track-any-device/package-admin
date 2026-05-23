<?php

namespace TrackAnyDevice\Admin\Filament\Resources\Assignees\Schemas;

use TrackAnyDevice\Core\Enums\AssigneeStatus;
use Filament\Forms\Components\KeyValue;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class AssigneeForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Identity')
                    ->columns(2)
                    ->schema([
                        Select::make('assignee_type_id')
                            ->label('Assignee Type')
                            ->relationship(
                                name: 'assigneeType',
                                titleAttribute: 'name',
                                modifyQueryUsing: fn ($query) => $query->where('is_active', true)->orderBy('sort_order'),
                            )
                            ->required()->searchable()->preload()->columnSpanFull(),
                        TextInput::make('name')->required()->maxLength(150),
                        TextInput::make('code')->required()->maxLength(50)->unique(ignoreRecord: true)->placeholder('ASN-00001'),
                        Select::make('status')
                            ->options(collect(AssigneeStatus::cases())->mapWithKeys(fn (AssigneeStatus $s) => [$s->value => $s->label()]))
                            ->required()->default(AssigneeStatus::Active->value)->columnSpanFull(),
                    ]),
                Section::make('Metadata')
                    ->description('Type-specific fields defined by the Assignee Type.')
                    ->schema([
                        KeyValue::make('metadata')->keyLabel('Field')->valueLabel('Value')->columnSpanFull(),
                        Textarea::make('notes')->rows(2)->columnSpanFull(),
                    ])
                    ->collapsed(),
            ]);
    }
}
