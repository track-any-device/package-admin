<?php

namespace TrackAnyDevice\Admin\Filament\Resources\Drivers;

use TrackAnyDevice\Admin\Filament\Resources\Drivers\Pages\CreateDriver;
use TrackAnyDevice\Admin\Filament\Resources\Drivers\Pages\EditDriver;
use TrackAnyDevice\Admin\Filament\Resources\Drivers\Pages\ListDrivers;
use TrackAnyDevice\Admin\Filament\Resources\Drivers\Pages\ViewDriver;
use TrackAnyDevice\Admin\Filament\Resources\Drivers\Schemas\DriverForm;
use TrackAnyDevice\Admin\Filament\Resources\Drivers\Tables\DriversTable;
use TrackAnyDevice\Core\Models\Driver;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use TrackAnyDevice\Admin\Concerns\HasDepartmentAccess;
use TrackAnyDevice\Core\Enums\StaffDepartment;

class DriverResource extends Resource
{
    use HasDepartmentAccess;

    protected static ?string $model = Driver::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedCog;

    protected static string|\UnitEnum|null $navigationGroup = 'Engineering';

    public static function form(Schema $schema): Schema
    {
        return DriverForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return DriversTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [];
    }

    protected static function getAllowedDepartments(): array
    {
        return [StaffDepartment::Engineering];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListDrivers::route('/'),
            'create' => CreateDriver::route('/create'),
            'view' => ViewDriver::route('/{record}'),
            'edit' => EditDriver::route('/{record}/edit'),
        ];
    }
}
