<?php

namespace TrackAnyDevice\Admin\Filament\Resources\WarehouseLogs;

use TrackAnyDevice\Admin\Concerns\HasDepartmentAccess;
use TrackAnyDevice\Admin\Filament\Resources\WarehouseLogs\Pages\ListWarehouseLogs;
use TrackAnyDevice\Admin\Filament\Resources\WarehouseLogs\Pages\ViewWarehouseLog;
use TrackAnyDevice\Admin\Filament\Resources\WarehouseLogs\Tables\WarehouseLogsTable;
use TrackAnyDevice\Core\Enums\StaffDepartment;
use TrackAnyDevice\Core\Models\WarehouseLog;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class WarehouseLogResource extends Resource
{
    use HasDepartmentAccess;

    protected static ?string $model = WarehouseLog::class;
    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedClipboardDocumentCheck;
    protected static string|\UnitEnum|null $navigationGroup = 'Warehouse';
    protected static ?int $navigationSort = 3;
    protected static ?string $navigationLabel = 'Movement Log';

    protected static function getAllowedDepartments(): array
    {
        return [StaffDepartment::Warehouse];
    }

    public static function canCreate(): bool
    {
        return false;
    }

    public static function table(Table $table): Table
    {
        return WarehouseLogsTable::configure($table);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListWarehouseLogs::route('/'),
            'view' => ViewWarehouseLog::route('/{record}'),
        ];
    }
}
