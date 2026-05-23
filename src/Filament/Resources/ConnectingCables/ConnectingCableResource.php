<?php

namespace TrackAnyDevice\Admin\Filament\Resources\ConnectingCables;

use TrackAnyDevice\Admin\Filament\Resources\ConnectingCables\Pages\CreateConnectingCable;
use TrackAnyDevice\Admin\Filament\Resources\ConnectingCables\Pages\EditConnectingCable;
use TrackAnyDevice\Admin\Filament\Resources\ConnectingCables\Pages\ListConnectingCables;
use TrackAnyDevice\Admin\Filament\Resources\ConnectingCables\Pages\ViewConnectingCable;
use TrackAnyDevice\Admin\Filament\Resources\ConnectingCables\Schemas\ConnectingCableForm;
use TrackAnyDevice\Admin\Filament\Resources\ConnectingCables\Tables\ConnectingCablesTable;
use TrackAnyDevice\Core\Models\ConnectingCable;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class ConnectingCableResource extends Resource
{
    protected static ?string $model = ConnectingCable::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedLink;

    protected static string|\UnitEnum|null $navigationGroup = 'Catalogue';

    public static function form(Schema $schema): Schema
    {
        return ConnectingCableForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return ConnectingCablesTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListConnectingCables::route('/'),
            'create' => CreateConnectingCable::route('/create'),
            'view' => ViewConnectingCable::route('/{record}'),
            'edit' => EditConnectingCable::route('/{record}/edit'),
        ];
    }
}
