<?php

namespace TrackAnyDevice\Admin\Filament\Resources\Sensors;

use TrackAnyDevice\Admin\Filament\Resources\Sensors\Pages\CreateSensor;
use TrackAnyDevice\Admin\Filament\Resources\Sensors\Pages\EditSensor;
use TrackAnyDevice\Admin\Filament\Resources\Sensors\Pages\ListSensors;
use TrackAnyDevice\Admin\Filament\Resources\Sensors\Pages\ViewSensor;
use TrackAnyDevice\Admin\Filament\Resources\Sensors\Schemas\SensorForm;
use TrackAnyDevice\Admin\Filament\Resources\Sensors\Tables\SensorsTable;
use TrackAnyDevice\Core\Models\Sensor;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use TrackAnyDevice\Admin\Concerns\HasDepartmentAccess;
use TrackAnyDevice\Core\Enums\StaffDepartment;

class SensorResource extends Resource
{
    use HasDepartmentAccess;

    protected static ?string $model = Sensor::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedAdjustmentsHorizontal;

    protected static string|\UnitEnum|null $navigationGroup = 'Engineering';

    public static function form(Schema $schema): Schema
    {
        return SensorForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return SensorsTable::configure($table);
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
            'index' => ListSensors::route('/'),
            'create' => CreateSensor::route('/create'),
            'view' => ViewSensor::route('/{record}'),
            'edit' => EditSensor::route('/{record}/edit'),
        ];
    }
}
