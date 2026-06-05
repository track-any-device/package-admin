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
use TrackAnyDevice\Admin\Concerns\HasDepartmentAccess;
use TrackAnyDevice\Core\Enums\StaffDepartment;

class ProductResource extends Resource
{
    use HasDepartmentAccess;

    protected static ?string $model = Product::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedShoppingBag;

    protected static string|\UnitEnum|null $navigationGroup = 'Sales';

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

    /** @return list<StaffDepartment> */
    public static function getAllowedDepartments(): array
    {
        return [StaffDepartment::Sales];
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
