<?php

namespace TrackAnyDevice\Admin\Filament\Resources\GsmNetworks;

use TrackAnyDevice\Admin\Filament\Resources\GsmNetworks\Pages\CreateGsmNetwork;
use TrackAnyDevice\Admin\Filament\Resources\GsmNetworks\Pages\EditGsmNetwork;
use TrackAnyDevice\Admin\Filament\Resources\GsmNetworks\Pages\ListGsmNetworks;
use TrackAnyDevice\Admin\Filament\Resources\GsmNetworks\Pages\ViewGsmNetwork;
use TrackAnyDevice\Admin\Filament\Resources\GsmNetworks\Schemas\GsmNetworkForm;
use TrackAnyDevice\Admin\Filament\Resources\GsmNetworks\Tables\GsmNetworksTable;
use TrackAnyDevice\Core\Models\GsmNetwork;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use TrackAnyDevice\Admin\Concerns\HasDepartmentAccess;
use TrackAnyDevice\Core\Enums\StaffDepartment;

class GsmNetworkResource extends Resource
{
    use HasDepartmentAccess;

    protected static ?string $model = GsmNetwork::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedSignal;

    protected static string|\UnitEnum|null $navigationGroup = 'Engineering';

    public static function form(Schema $schema): Schema
    {
        return GsmNetworkForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return GsmNetworksTable::configure($table);
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
            'index' => ListGsmNetworks::route('/'),
            'create' => CreateGsmNetwork::route('/create'),
            'view' => ViewGsmNetwork::route('/{record}'),
            'edit' => EditGsmNetwork::route('/{record}/edit'),
        ];
    }
}
