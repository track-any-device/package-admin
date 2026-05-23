<?php

namespace TrackAnyDevice\Admin\Filament\Resources\Devices;

use TrackAnyDevice\Admin\Filament\Resources\Devices\Pages\CreateDevice;
use TrackAnyDevice\Admin\Filament\Resources\Devices\Pages\EditDevice;
use TrackAnyDevice\Admin\Filament\Resources\Devices\Pages\ListDevices;
use TrackAnyDevice\Admin\Filament\Resources\Devices\Pages\ViewDevice;
use TrackAnyDevice\Admin\Filament\Resources\Devices\RelationManagers\BeatAssignmentsRelationManager;
use TrackAnyDevice\Admin\Filament\Resources\Devices\RelationManagers\DeviceAssignmentsRelationManager;
use TrackAnyDevice\Admin\Filament\Resources\Devices\Schemas\DeviceForm;
use TrackAnyDevice\Admin\Filament\Resources\Devices\Tables\DevicesTable;
use TrackAnyDevice\Core\Models\Device;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class DeviceResource extends Resource
{
    protected static ?string $model = Device::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedDevicePhoneMobile;

    protected static string|\UnitEnum|null $navigationGroup = 'Devices';

    protected static ?int $navigationSort = 1;

    public static function form(Schema $schema): Schema
    {
        return DeviceForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return DevicesTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            DeviceAssignmentsRelationManager::class,
            BeatAssignmentsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListDevices::route('/'),
            'create' => CreateDevice::route('/create'),
            'view' => ViewDevice::route('/{record}'),
            'edit' => EditDevice::route('/{record}/edit'),
        ];
    }
}
