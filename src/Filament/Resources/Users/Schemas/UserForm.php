<?php

namespace TrackAnyDevice\Admin\Filament\Resources\Users\Schemas;

use TrackAnyDevice\Core\Enums\Role;
use TrackAnyDevice\Core\Enums\StaffDepartment;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class UserForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Identity')
                    ->columns(2)
                    ->schema([
                        TextInput::make('name')
                            ->required()
                            ->maxLength(150),

                        TextInput::make('email')
                            ->email()
                            ->required()
                            ->unique(ignoreRecord: true)
                            ->maxLength(255),

                        Select::make('role')
                            ->options(collect(Role::cases())->mapWithKeys(
                                fn (Role $r) => [$r->value => $r->label()]
                            )->all())
                            ->required()
                            ->native(false),

                        Select::make('tenants')
                            ->label('Tenants')
                            ->relationship('tenants', 'name')
                            ->multiple()
                            ->nullable()
                            ->searchable()
                            ->preload()
                            ->placeholder('— No tenants attached —')
                            ->helperText('Attach a user to one or more tenants to promote them into the tenant portal. End users (role: User) are typically left blank.')
                            ->columnSpanFull(),

                        TextInput::make('password')
                            ->password()
                            ->revealable()
                            ->minLength(8)
                            ->dehydrateStateUsing(fn ($state) => filled($state) ? bcrypt($state) : null)
                            ->dehydrated(fn ($state) => filled($state))
                            ->required(fn (string $operation): bool => $operation === 'create')
                            ->helperText('Leave blank to keep existing password when editing.'),
                    ]),

                Section::make('Contact & Visibility')
                    ->columns(2)
                    ->schema([
                        TextInput::make('primary_contact')
                            ->label('Primary Contact (private)')
                            ->tel()
                            ->maxLength(50),

                        TextInput::make('public_contact')
                            ->label('Public Contact (shown to field users)')
                            ->tel()
                            ->maxLength(50),

                        Toggle::make('share_email')
                            ->label('Share email address with field users')
                            ->default(true),
                    ]),

                Section::make('Department Assignments')
                    ->description('Assign this staff member to one or more departments.')
                    ->schema([
                        Repeater::make('staffDepartmentEntries')
                            ->relationship()
                            ->label('')
                            ->schema([
                                Select::make('department')
                                    ->options(collect(StaffDepartment::cases())
                                        ->mapWithKeys(fn (StaffDepartment $d) => [$d->value => $d->label()])
                                        ->all())
                                    ->required()
                                    ->live()
                                    ->columnSpan(1),

                                Select::make('warehouse_id')
                                    ->relationship('warehouse', 'name')
                                    ->searchable()
                                    ->preload()
                                    ->nullable()
                                    ->visible(fn (callable $get) => $get('department') === StaffDepartment::Warehouse->value)
                                    ->columnSpan(1),

                                Toggle::make('is_workshop')
                                    ->label('Workshop access')
                                    ->visible(fn (callable $get) => $get('department') === StaffDepartment::Warehouse->value)
                                    ->columnSpan(1),
                            ])
                            ->columns(3)
                            ->defaultItems(0)
                            ->addActionLabel('Add Department')
                            ->reorderable(false)
                            ->columnSpanFull(),
                    ]),
            ]);
    }
}
