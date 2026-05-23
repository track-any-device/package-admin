<?php

namespace TrackAnyDevice\Admin\Filament\Resources\ChargingSets;

use TrackAnyDevice\Admin\Filament\Resources\ChargingSets\Pages\CreateChargingSet;
use TrackAnyDevice\Admin\Filament\Resources\ChargingSets\Pages\EditChargingSet;
use TrackAnyDevice\Admin\Filament\Resources\ChargingSets\Pages\ListChargingSets;
use TrackAnyDevice\Admin\Filament\Resources\ChargingSets\Pages\ViewChargingSet;
use TrackAnyDevice\Admin\Filament\Resources\ChargingSets\Schemas\ChargingSetForm;
use TrackAnyDevice\Admin\Filament\Resources\ChargingSets\Tables\ChargingSetsTable;
use TrackAnyDevice\Core\Models\ChargingSet;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class ChargingSetResource extends Resource
{
    protected static ?string $model = ChargingSet::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedBolt;

    protected static string|\UnitEnum|null $navigationGroup = 'Catalogue';

    public static function form(Schema $schema): Schema
    {
        return ChargingSetForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return ChargingSetsTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListChargingSets::route('/'),
            'create' => CreateChargingSet::route('/create'),
            'view' => ViewChargingSet::route('/{record}'),
            'edit' => EditChargingSet::route('/{record}/edit'),
        ];
    }
}
