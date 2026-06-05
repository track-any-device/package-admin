<?php

namespace TrackAnyDevice\Admin\Filament\Resources\Warehouses;

use TrackAnyDevice\Admin\Concerns\HasDepartmentAccess;
use TrackAnyDevice\Admin\Filament\Resources\Warehouses\Pages\CreateWarehouse;
use TrackAnyDevice\Admin\Filament\Resources\Warehouses\Pages\EditWarehouse;
use TrackAnyDevice\Admin\Filament\Resources\Warehouses\Pages\ListWarehouses;
use TrackAnyDevice\Admin\Filament\Resources\Warehouses\Pages\ViewWarehouse;
use TrackAnyDevice\Admin\Filament\Resources\Warehouses\Schemas\WarehouseForm;
use TrackAnyDevice\Admin\Filament\Resources\Warehouses\Tables\WarehousesTable;
use TrackAnyDevice\Core\Enums\StaffDepartment;
use TrackAnyDevice\Core\Models\Warehouse;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class WarehouseResource extends Resource
{
    use HasDepartmentAccess;

    protected static ?string $model = Warehouse::class;
    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedBuildingStorefront;
    protected static string|\UnitEnum|null $navigationGroup = 'Warehouse';
    protected static ?int $navigationSort = 1;

    protected static function getAllowedDepartments(): array
    {
        return [StaffDepartment::Warehouse];
    }

    public static function form(Schema $schema): Schema
    {
        return WarehouseForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return WarehousesTable::configure($table);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListWarehouses::route('/'),
            'create' => CreateWarehouse::route('/create'),
            'view' => ViewWarehouse::route('/{record}'),
            'edit' => EditWarehouse::route('/{record}/edit'),
        ];
    }
}
