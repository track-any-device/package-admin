<?php

namespace TrackAnyDevice\Admin\Filament\Resources\DeviceOrders;

use TrackAnyDevice\Admin\Filament\Resources\DeviceOrders\Pages\CreateDeviceOrder;
use TrackAnyDevice\Admin\Filament\Resources\DeviceOrders\Pages\EditDeviceOrder;
use TrackAnyDevice\Admin\Filament\Resources\DeviceOrders\Pages\ListDeviceOrders;
use TrackAnyDevice\Admin\Filament\Resources\DeviceOrders\Pages\ViewDeviceOrder;
use TrackAnyDevice\Admin\Filament\Resources\DeviceOrders\Schemas\DeviceOrderForm;
use TrackAnyDevice\Admin\Filament\Resources\DeviceOrders\Tables\DeviceOrdersTable;
use TrackAnyDevice\Core\Models\DeviceOrder;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use TrackAnyDevice\Admin\Concerns\HasDepartmentAccess;
use TrackAnyDevice\Core\Enums\StaffDepartment;

class DeviceOrderResource extends Resource
{
    use HasDepartmentAccess;

    protected static ?string $model = DeviceOrder::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedShoppingCart;

    protected static string|\UnitEnum|null $navigationGroup = 'Sales';

    protected static ?int $navigationSort = 2;

    public static function form(Schema $schema): Schema
    {
        return DeviceOrderForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return DeviceOrdersTable::configure($table);
    }

    /** @return list<StaffDepartment> */
    public static function getAllowedDepartments(): array
    {
        return [StaffDepartment::Sales];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListDeviceOrders::route('/'),
            'create' => CreateDeviceOrder::route('/create'),
            'view' => ViewDeviceOrder::route('/{record}'),
            'edit' => EditDeviceOrder::route('/{record}/edit'),
        ];
    }
}
