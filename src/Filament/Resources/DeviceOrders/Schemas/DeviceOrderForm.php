<?php

namespace TrackAnyDevice\Admin\Filament\Resources\DeviceOrders\Schemas;

use TrackAnyDevice\Core\Enums\DeviceOrderStatus;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class DeviceOrderForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Order Details')
                    ->columns(2)
                    ->schema([
                        Select::make('tenant_id')
                            ->label('Tenant')
                            ->relationship('tenant', 'name')
                            ->required()
                            ->searchable()
                            ->preload(),

                        Select::make('user_id')
                            ->label('Placed By (End User)')
                            ->relationship('user', 'name')
                            ->nullable()
                            ->searchable()
                            ->preload()
                            ->helperText('Optional. Set when the order was placed through /store by an end user.'),

                        Select::make('device_type_id')
                            ->label('Device Type')
                            ->relationship('deviceType', 'name')
                            ->required()
                            ->searchable()
                            ->preload(),

                        Select::make('device_id')
                            ->label('Assign Device (Stock)')
                            ->relationship(
                                name: 'device',
                                titleAttribute: 'name',
                                modifyQueryUsing: fn ($query) => $query->whereNull('tenant_id'),
                            )
                            ->nullable()
                            ->searchable()
                            ->preload()
                            ->helperText('Pick a stock device to assign when confirming the order.')
                            ->columnSpanFull(),

                        Select::make('status')
                            ->options(collect(DeviceOrderStatus::cases())->mapWithKeys(
                                fn (DeviceOrderStatus $s) => [$s->value => $s->label()]
                            ))
                            ->required()
                            ->default(DeviceOrderStatus::Pending->value),
                    ]),

                Section::make('Notes')
                    ->columns(1)
                    ->schema([
                        Textarea::make('notes')
                            ->label('Tenant Notes')
                            ->rows(3)
                            ->disabled(),

                        Textarea::make('admin_notes')
                            ->label('Admin Notes')
                            ->rows(3),
                    ]),
            ]);
    }
}
