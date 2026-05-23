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

class SensorResource extends Resource
{
    protected static ?string $model = Sensor::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedAdjustmentsHorizontal;

    protected static string|\UnitEnum|null $navigationGroup = 'Catalogue';

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
