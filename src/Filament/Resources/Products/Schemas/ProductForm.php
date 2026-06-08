<?php

namespace TrackAnyDevice\Admin\Filament\Resources\Products\Schemas;

use TrackAnyDevice\Core\Enums\ProductType;
use TrackAnyDevice\Core\Models\ChargingSet;
use TrackAnyDevice\Core\Models\Chip;
use TrackAnyDevice\Core\Models\ComputeBoard;
use TrackAnyDevice\Core\Models\ConnectingCable;
use TrackAnyDevice\Core\Models\Country;
use TrackAnyDevice\Core\Models\DeviceType;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class ProductForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('Product')->columns(2)->schema([
                TextInput::make('name')->required(),
                TextInput::make('sku')->required()->unique(ignoreRecord: true),
                Select::make('product_type')
                    ->options(collect(ProductType::cases())->mapWithKeys(fn ($t) => [$t->value => $t->label()])->all())
                    ->live()
                    ->required(),
                Select::make('productable_id')
                    ->label('Linked Record')
                    ->options(function (callable $get) {
                        $type = $get('product_type');

                        return match ($type) {
                            ProductType::DeviceType->value => DeviceType::pluck('name', 'id')->all(),
                            ProductType::Chip->value => Chip::pluck('name', 'id')->all(),
                            ProductType::ComputeBoard->value => ComputeBoard::pluck('name', 'id')->all(),
                            ProductType::ConnectingCable->value => ConnectingCable::pluck('name', 'id')->all(),
                            ProductType::ChargingSet->value => ChargingSet::pluck('name', 'id')->all(),
                            default => [],
                        };
                    })
                    ->afterStateUpdated(function ($state, $get, callable $set) {
                        $type = $get('product_type');
                        $set('productable_type', match ($type) {
                            ProductType::DeviceType->value => DeviceType::class,
                            ProductType::Chip->value => Chip::class,
                            ProductType::ComputeBoard->value => ComputeBoard::class,
                            ProductType::ConnectingCable->value => ConnectingCable::class,
                            ProductType::ChargingSet->value => ChargingSet::class,
                            default => null,
                        });
                    })
                    ->required(),
                TextInput::make('productable_type')->hidden(),
                TextInput::make('price')
                    ->label('Base Price')
                    ->numeric()
                    ->required()
                    ->step(0.01)
                    ->helperText('In the default country\'s currency. Other currencies are auto-derived via Country.conversion_rate × markup.'),
                TextInput::make('currency')
                    ->required()
                    ->maxLength(3)
                    ->default(fn () => Country::default()?->currency_code ?? 'PKR'),
                TextInput::make('stock')->numeric()->default(0),
                TextInput::make('max_order_quantity')
                    ->label('Max Order Qty')
                    ->numeric()
                    ->default(10)
                    ->minValue(1)
                    ->helperText('Direct checkout limit per order. Orders above this require contacting sales.'),
                Toggle::make('is_active')->default(true),
                Textarea::make('description')->rows(2)->columnSpanFull(),
            ]),

            Section::make('Media')->columns(2)->schema([
                FileUpload::make('image')
                    ->image()
                    ->directory('products')
                    ->disk('public')
                    ->maxSize(4096)
                    ->columnSpanFull(),
                FileUpload::make('images')
                    ->multiple()
                    ->image()
                    ->directory('products/gallery')
                    ->disk('public')
                    ->maxSize(4096)
                    ->reorderable()
                    ->columnSpanFull(),
            ]),
        ]);
    }
}
