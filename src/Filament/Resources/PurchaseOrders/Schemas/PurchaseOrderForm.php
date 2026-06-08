<?php

namespace TrackAnyDevice\Admin\Filament\Resources\PurchaseOrders\Schemas;

use TrackAnyDevice\Core\Enums\PurchaseOrderStatus;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class PurchaseOrderForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('Order Details')
                ->columns(2)
                ->schema([
                    TextInput::make('vendor_name')
                        ->required()
                        ->maxLength(255),

                    Select::make('device_type_id')
                        ->label('Device Type')
                        ->relationship(
                            name: 'deviceType',
                            titleAttribute: 'name',
                            modifyQueryUsing: fn ($query) => $query->where('is_active', true)->orderBy('name'),
                        )
                        ->required(),

                    Select::make('warehouse_id')
                        ->relationship('warehouse', 'name')
                        ->required()
                        ->searchable()
                        ->preload(),

                    Select::make('status')
                        ->options(collect(PurchaseOrderStatus::cases())
                            ->mapWithKeys(fn (PurchaseOrderStatus $s) => [$s->value => $s->label()])
                            ->all())
                        ->default(PurchaseOrderStatus::Draft->value)
                        ->required(),
                ]),

            Section::make('Quantities & Cost')
                ->columns(3)
                ->schema([
                    TextInput::make('quantity_ordered')
                        ->required()
                        ->numeric()
                        ->minValue(1),

                    TextInput::make('quantity_received')
                        ->numeric()
                        ->default(0)
                        ->disabled(),

                    TextInput::make('unit_cost')
                        ->numeric()
                        ->prefix('$')
                        ->nullable(),

                    TextInput::make('currency')
                        ->maxLength(3)
                        ->default('USD'),

                    DatePicker::make('expected_at')
                        ->label('Expected Delivery')
                        ->nullable(),
                ]),

            Section::make('Notes')
                ->collapsed()
                ->schema([
                    Textarea::make('notes')
                        ->rows(3)
                        ->columnSpanFull(),
                ]),
        ]);
    }
}
