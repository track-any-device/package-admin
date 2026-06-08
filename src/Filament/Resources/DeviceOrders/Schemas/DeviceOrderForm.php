<?php

namespace TrackAnyDevice\Admin\Filament\Resources\DeviceOrders\Schemas;

use TrackAnyDevice\Core\Enums\DeviceOrderStatus;
use TrackAnyDevice\Core\Enums\PaymentMethod;
use Filament\Forms\Components\KeyValue;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
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
                            ->nullable()
                            ->searchable()
                            ->preload()
                            ->helperText('Nullable for direct shop purchases.'),

                        Select::make('user_id')
                            ->label('Placed By (End User)')
                            ->relationship('user', 'name')
                            ->nullable()
                            ->searchable()
                            ->preload(),

                        Select::make('product_id')
                            ->label('Product')
                            ->relationship('product', 'name')
                            ->nullable()
                            ->searchable()
                            ->preload(),

                        Select::make('device_type_id')
                            ->label('Device Type')
                            ->relationship(
                                name: 'deviceType',
                                titleAttribute: 'name',
                                modifyQueryUsing: fn ($query) => $query->where('is_active', true)->orderBy('name'),
                            )
                            ->nullable(),

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
                            ->helperText('Pick a stock device to assign when confirming the order.'),

                        Select::make('status')
                            ->options(collect(DeviceOrderStatus::cases())->mapWithKeys(
                                fn (DeviceOrderStatus $s) => [$s->value => $s->label()]
                            ))
                            ->required()
                            ->default(DeviceOrderStatus::Pending->value),

                        TextInput::make('claim_code')
                            ->label('Claim Code')
                            ->maxLength(8)
                            ->disabled()
                            ->helperText('Auto-generated. Users scan this to claim devices after delivery.'),

                        TextInput::make('quantity')
                            ->numeric()
                            ->default(1)
                            ->minValue(1),
                    ]),

                Section::make('Payment & Pricing')
                    ->columns(2)
                    ->schema([
                        Select::make('payment_method')
                            ->options(collect(PaymentMethod::cases())->mapWithKeys(
                                fn (PaymentMethod $p) => [$p->value => $p->label()]
                            ))
                            ->default(PaymentMethod::CashOnDelivery->value),

                        TextInput::make('total_amount')
                            ->label('Total Amount')
                            ->numeric()
                            ->step(0.01),

                        TextInput::make('currency')
                            ->maxLength(3)
                            ->default('PKR'),
                    ]),

                Section::make('Shipping')
                    ->columns(2)
                    ->schema([
                        TextInput::make('shipping_name')
                            ->label('Recipient Name'),

                        TextInput::make('shipping_phone')
                            ->label('Phone')
                            ->tel(),

                        KeyValue::make('shipping_address')
                            ->label('Shipping Address')
                            ->columnSpanFull(),

                        KeyValue::make('billing_address')
                            ->label('Billing Address')
                            ->helperText('Same as shipping for COD orders.')
                            ->columnSpanFull(),
                    ]),

                Section::make('Notes')
                    ->columns(1)
                    ->schema([
                        Textarea::make('notes')
                            ->label('Customer Notes')
                            ->rows(3)
                            ->disabled(),

                        Textarea::make('admin_notes')
                            ->label('Admin Notes')
                            ->rows(3),
                    ]),
            ]);
    }
}
