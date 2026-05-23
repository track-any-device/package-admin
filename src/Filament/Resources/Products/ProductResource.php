<?php

namespace TrackAnyDevice\Admin\Filament\Resources\Products;

use TrackAnyDevice\Admin\Filament\Resources\Products\Pages\CreateProduct;
use TrackAnyDevice\Admin\Filament\Resources\Products\Pages\EditProduct;
use TrackAnyDevice\Admin\Filament\Resources\Products\Pages\ListProducts;
use TrackAnyDevice\Admin\Filament\Resources\Products\Pages\ViewProduct;
use TrackAnyDevice\Admin\Filament\Resources\Products\Schemas\ProductForm;
use TrackAnyDevice\Admin\Filament\Resources\Products\Tables\ProductsTable;
use TrackAnyDevice\Core\Models\Product;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class ProductResource extends Resource
{
    protected static ?string $model = Product::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedShoppingBag;

    protected static string|\UnitEnum|null $navigationGroup = 'Catalogue';

    public static function form(Schema $schema): Schema
    {
        return ProductForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return ProductsTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListProducts::route('/'),
            'create' => CreateProduct::route('/create'),
            'view' => ViewProduct::route('/{record}'),
            'edit' => EditProduct::route('/{record}/edit'),
        ];
    }
}
