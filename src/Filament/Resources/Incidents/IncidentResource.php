<?php

namespace TrackAnyDevice\Admin\Filament\Resources\Incidents;

use TrackAnyDevice\Admin\Filament\Resources\Incidents\Pages\EditIncident;
use TrackAnyDevice\Admin\Filament\Resources\Incidents\Pages\ListIncidents;
use TrackAnyDevice\Admin\Filament\Resources\Incidents\Pages\ViewIncident;
use TrackAnyDevice\Admin\Filament\Resources\Incidents\Schemas\IncidentForm;
use TrackAnyDevice\Admin\Filament\Resources\Incidents\Tables\IncidentsTable;
use TrackAnyDevice\Core\Models\Incident;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class IncidentResource extends Resource
{
    protected static ?string $model = Incident::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedExclamationTriangle;

    protected static string|\UnitEnum|null $navigationGroup = 'Operations';

    protected static ?int $navigationSort = 2;

    public static function form(Schema $schema): Schema
    {
        return IncidentForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return IncidentsTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListIncidents::route('/'),
            'view' => ViewIncident::route('/{record}'),
            'edit' => EditIncident::route('/{record}/edit'),
        ];
    }
}
