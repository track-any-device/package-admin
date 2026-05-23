<?php

namespace TrackAnyDevice\Admin\Filament\Resources\Devices\Schemas;

use TrackAnyDevice\Core\Enums\DeviceStatus;
use TrackAnyDevice\Core\Models\Sensor;
use Filament\Forms\Components\CheckboxList;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class DeviceForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Tenant Assignment')
                    ->columns(2)
                    ->schema([
                        Select::make('tenant_id')
                            ->label('Assigned Tenant')
                            ->relationship('tenant', 'name')
                            ->nullable()
                            ->searchable()
                            ->preload()
                            ->placeholder('— Stock (not yet assigned) —')
                            ->helperText('Leave blank to keep device in stock. Assign to make it available to a tenant.')
                            ->columnSpanFull(),

                        Toggle::make('is_visible_to_tenant')
                            ->label('Visible to Tenant')
                            ->helperText('When enabled, the assigned tenant can see this device in their portal.')
                            ->default(false)
                            ->columnSpanFull(),
                    ]),

                Section::make('Identity')
                    ->columns(2)
                    ->schema([
                        Select::make('device_type_id')
                            ->label('Device Type')
                            ->relationship(
                                name: 'deviceType',
                                titleAttribute: 'name',
                                modifyQueryUsing: fn ($query) => $query->where('is_active', true),
                            )
                            ->required()
                            ->searchable()
                            ->preload()
                            ->columnSpanFull(),

                        TextInput::make('name')
                            ->required()
                            ->maxLength(100),

                        Select::make('status')
                            ->options(collect(DeviceStatus::cases())->mapWithKeys(
                                fn (DeviceStatus $s) => [$s->value => $s->label()]
                            ))
                            ->required()
                            ->default(DeviceStatus::Inventory->value),

                        Toggle::make('is_approved')
                            ->label('Approved')
                            ->helperText('Only approved devices may connect via JT808.')
                            ->default(true)
                            ->columnSpanFull(),
                    ]),

                Section::make('SIM & Communication')
                    ->columns(2)
                    ->schema([
                        TextInput::make('imei')
                            ->label('IMEI')
                            ->required()
                            ->maxLength(20)
                            ->unique(ignoreRecord: true),

                        TextInput::make('gsm_number')
                            ->label('GSM Number')
                            ->tel()
                            ->maxLength(20)
                            ->unique(ignoreRecord: true)
                            ->helperText('Phone number used for SMS commands'),

                        TextInput::make('sim_number')
                            ->label('SIM Number')
                            ->maxLength(30)
                            ->unique(ignoreRecord: true),

                        TextInput::make('password')
                            ->label('Device Password')
                            ->required()
                            ->maxLength(20)
                            ->default('123456'),
                    ]),

                Section::make('Sensors')
                    ->description('Override sensor capabilities for this device. Leave empty to inherit from device type.')
                    ->schema([
                        CheckboxList::make('sensors')
                            ->relationship('sensors', 'name')
                            ->descriptions(fn () => Sensor::orderBy('sort_order')
                                ->pluck('description', 'id')
                                ->all())
                            ->columns(3)
                            ->gridDirection('row')
                            ->helperText('If nothing is selected, the device inherits sensors defined on its Device Type.'),
                    ])
                    ->collapsed(),

                Section::make('Notes')
                    ->schema([
                        Textarea::make('notes')
                            ->rows(3)
                            ->columnSpanFull(),
                    ])
                    ->collapsed(),
            ]);
    }
}
