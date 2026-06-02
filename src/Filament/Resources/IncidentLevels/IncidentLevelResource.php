<?php

namespace TrackAnyDevice\Admin\Filament\Resources\IncidentLevels;

use TrackAnyDevice\Admin\Filament\Resources\IncidentLevels\Pages\CreateIncidentLevel;
use TrackAnyDevice\Admin\Filament\Resources\IncidentLevels\Pages\EditIncidentLevel;
use TrackAnyDevice\Admin\Filament\Resources\IncidentLevels\Pages\ListIncidentLevels;
use TrackAnyDevice\Admin\Filament\Resources\IncidentLevels\Pages\ViewIncidentLevel;
use TrackAnyDevice\Admin\Filament\Resources\IncidentLevels\Schemas\IncidentLevelForm;
use TrackAnyDevice\Admin\Filament\Resources\IncidentLevels\Tables\IncidentLevelsTable;
use TrackAnyDevice\Core\Models\IncidentLevel;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class IncidentLevelResource extends Resource
{
    protected static ?string $model = IncidentLevel::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedArrowsUpDown;

    protected static string|\UnitEnum|null $navigationGroup = 'Operations';

    protected static ?int $navigationSort = 4;

    public static function form(Schema $schema): Schema
    {
        return IncidentLevelForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return IncidentLevelsTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListIncidentLevels::route('/'),
            'create' => CreateIncidentLevel::route('/create'),
            'view' => ViewIncidentLevel::route('/{record}'),
            'edit' => EditIncidentLevel::route('/{record}/edit'),
        ];
    }
}
