<?php

namespace TrackAnyDevice\Admin\Filament\Resources\PurchaseOrders;

use TrackAnyDevice\Admin\Concerns\HasDepartmentAccess;
use TrackAnyDevice\Admin\Filament\Resources\PurchaseOrders\Pages\CreatePurchaseOrder;
use TrackAnyDevice\Admin\Filament\Resources\PurchaseOrders\Pages\EditPurchaseOrder;
use TrackAnyDevice\Admin\Filament\Resources\PurchaseOrders\Pages\ListPurchaseOrders;
use TrackAnyDevice\Admin\Filament\Resources\PurchaseOrders\Pages\ViewPurchaseOrder;
use TrackAnyDevice\Admin\Filament\Resources\PurchaseOrders\Schemas\PurchaseOrderForm;
use TrackAnyDevice\Admin\Filament\Resources\PurchaseOrders\Tables\PurchaseOrdersTable;
use TrackAnyDevice\Core\Enums\PurchaseOrderStatus;
use TrackAnyDevice\Core\Enums\StaffDepartment;
use TrackAnyDevice\Core\Models\PurchaseOrder;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class PurchaseOrderResource extends Resource
{
    use HasDepartmentAccess;

    protected static ?string $model = PurchaseOrder::class;
    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedClipboardDocumentList;
    protected static string|\UnitEnum|null $navigationGroup = 'Procurement';
    protected static ?int $navigationSort = 2;
    protected static ?string $navigationLabel = 'Purchase Orders';

    protected static function getAllowedDepartments(): array
    {
        return [StaffDepartment::Procurement, StaffDepartment::Warehouse];
    }

    public static function form(Schema $schema): Schema
    {
        return PurchaseOrderForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return PurchaseOrdersTable::configure($table);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListPurchaseOrders::route('/'),
            'create' => CreatePurchaseOrder::route('/create'),
            'view' => ViewPurchaseOrder::route('/{record}'),
            'edit' => EditPurchaseOrder::route('/{record}/edit'),
        ];
    }

    public static function getNavigationBadge(): ?string
    {
        $pending = PurchaseOrder::where('status', PurchaseOrderStatus::Submitted)->count();

        return $pending > 0 ? (string) $pending : null;
    }

    public static function getNavigationBadgeColor(): ?string
    {
        return 'warning';
    }
}
