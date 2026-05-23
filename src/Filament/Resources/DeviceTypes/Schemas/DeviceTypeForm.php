<?php

namespace TrackAnyDevice\Admin\Filament\Resources\DeviceTypes\Schemas;

use TrackAnyDevice\Core\Models\Sensor;
use Filament\Forms\Components\CheckboxList;
use Filament\Forms\Components\ColorPicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Illuminate\Support\Str;

class DeviceTypeForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Device Type Details')
                    ->columns(2)
                    ->schema([
                        TextInput::make('name')
                            ->required()
                            ->maxLength(100)
                            ->live(onBlur: true)
                            ->afterStateUpdated(fn (string $state, callable $set) => $set('slug', Str::slug($state)))
                            ->columnSpan(1),

                        TextInput::make('slug')
                            ->required()
                            ->maxLength(100)
                            ->unique(ignoreRecord: true)
                            ->columnSpan(1),

                        TextInput::make('driver_class')
                            ->required()
                            ->maxLength(255)
                            ->placeholder('App\\Drivers\\P901Driver')
                            ->helperText('SMS command builder class. All devices receive data over JT808 TCP.')
                            ->columnSpanFull(),

                        Textarea::make('description')
                            ->rows(2)
                            ->columnSpanFull(),

                        Toggle::make('is_active')->default(true),
                        Toggle::make('is_featured')
                            ->helperText('Show on homepage Products section'),
                    ]),

                Section::make('Pricing (deprecated — manage on the linked Product)')
                    ->description('These fields remain as fallback for legacy code paths. Create a Product linked to this Device Type for proper currency-aware pricing.')
                    ->collapsed()
                    ->columns(2)
                    ->schema([
                        TextInput::make('price_usd')
                            ->label('Price (USD)')
                            ->numeric()
                            ->prefix('$')
                            ->minValue(0)
                            ->step(0.01)
                            ->nullable(),

                        TextInput::make('price_pkr')
                            ->label('Price (PKR)')
                            ->numeric()
                            ->prefix('₨')
                            ->minValue(0)
                            ->step(1)
                            ->nullable(),
                    ]),

                Section::make('Stock & Ordering')
                    ->description('Control how many units a customer can order. Bulk orders redirect to "Contact Us".')
                    ->columns(4)
                    ->schema([
                        TextInput::make('quantity')
                            ->label('Warehouse Stock')
                            ->numeric()
                            ->minValue(0)
                            ->default(0)
                            ->helperText('Current units in stock'),

                        TextInput::make('min_quantity')
                            ->label('Min Order')
                            ->numeric()
                            ->minValue(1)
                            ->default(1)
                            ->helperText('Minimum units per order'),

                        TextInput::make('quantity_multiple')
                            ->label('Order Multiple')
                            ->numeric()
                            ->minValue(1)
                            ->default(1)
                            ->helperText('Must order in multiples of this (e.g. 5 → 5, 10, 15…)'),

                        TextInput::make('max_quantity')
                            ->label('Max Order')
                            ->numeric()
                            ->minValue(1)
                            ->nullable()
                            ->helperText('Leave blank for no cap'),

                        TextInput::make('bulk_quantity')
                            ->label('Bulk Threshold')
                            ->numeric()
                            ->minValue(1)
                            ->nullable()
                            ->helperText('Above this qty → "Contact Us for bulk pricing"')
                            ->columnSpanFull(),
                    ]),

                Section::make('Badge')
                    ->description('Optional promotional badge shown on the product card.')
                    ->columns(2)
                    ->schema([
                        TextInput::make('badge_label')
                            ->label('Badge Text')
                            ->maxLength(30)
                            ->placeholder('Best Seller, New, Solar Powered…'),

                        ColorPicker::make('badge_color')
                            ->label('Badge Color'),
                    ])
                    ->collapsed(),

                Section::make('Images')
                    ->columns(2)
                    ->schema([
                        FileUpload::make('image')
                            ->label('Main Image')
                            ->image()
                            ->directory('device-types')
                            ->imageEditor()
                            ->imageEditorAspectRatios(['4:3', '1:1', '16:9'])
                            ->nullable()
                            ->columnSpan(1),

                        FileUpload::make('images')
                            ->label('Gallery Images')
                            ->image()
                            ->multiple()
                            ->directory('device-types/gallery')
                            ->nullable()
                            ->reorderable()
                            ->columnSpan(1),
                    ]),

                Section::make('Sensors')
                    ->description('Select the sensors this device type has. Individual devices can override this list.')
                    ->schema([
                        CheckboxList::make('sensors')
                            ->relationship('sensors', 'name')
                            ->descriptions(fn () => Sensor::orderBy('sort_order')
                                ->pluck('description', 'id')
                                ->all())
                            ->columns(3)
                            ->gridDirection('row'),
                    ]),
            ]);
    }
}
