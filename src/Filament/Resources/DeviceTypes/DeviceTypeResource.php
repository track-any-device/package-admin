<?php

namespace TrackAnyDevice\Admin\Filament\Resources\DeviceTypes;

use TrackAnyDevice\Admin\Filament\Resources\Cms\PageResource\RelationManagers\SectionsRelationManager;
use TrackAnyDevice\Admin\Filament\Resources\DeviceTypes\Pages\CreateDeviceType;
use TrackAnyDevice\Admin\Filament\Resources\DeviceTypes\Pages\EditDeviceType;
use TrackAnyDevice\Admin\Filament\Resources\DeviceTypes\Pages\ListDeviceTypes;
use TrackAnyDevice\Admin\Filament\Resources\DeviceTypes\Pages\ViewDeviceType;
use TrackAnyDevice\Admin\Filament\Resources\DeviceTypes\Schemas\DeviceTypeForm;
use TrackAnyDevice\Admin\Filament\Resources\DeviceTypes\Tables\DeviceTypesTable;
use TrackAnyDevice\Core\Models\DeviceType;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class DeviceTypeResource extends Resource
{
    protected static ?string $model = DeviceType::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedCpuChip;

    protected static string|\UnitEnum|null $navigationGroup = 'Device Types';

    protected static ?int $navigationSort = 2;

    public static function form(Schema $schema): Schema
    {
        return DeviceTypeForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return DeviceTypesTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [SectionsRelationManager::class];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListDeviceTypes::route('/'),
            'create' => CreateDeviceType::route('/create'),
            'view' => ViewDeviceType::route('/{record}'),
            'edit' => EditDeviceType::route('/{record}/edit'),
        ];
    }
}
