<?php

namespace TrackAnyDevice\Admin\Filament\Resources\IncidentStatusOptions;

use TrackAnyDevice\Admin\Filament\Resources\IncidentStatusOptions\Pages\CreateIncidentStatusOption;
use TrackAnyDevice\Admin\Filament\Resources\IncidentStatusOptions\Pages\EditIncidentStatusOption;
use TrackAnyDevice\Admin\Filament\Resources\IncidentStatusOptions\Pages\ListIncidentStatusOptions;
use TrackAnyDevice\Admin\Filament\Resources\IncidentStatusOptions\Pages\ViewIncidentStatusOption;
use TrackAnyDevice\Admin\Filament\Resources\IncidentStatusOptions\Schemas\IncidentStatusOptionForm;
use TrackAnyDevice\Admin\Filament\Resources\IncidentStatusOptions\Tables\IncidentStatusOptionsTable;
use TrackAnyDevice\Core\Models\IncidentStatusOption;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class IncidentStatusOptionResource extends Resource
{
    protected static ?string $model = IncidentStatusOption::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedListBullet;

    protected static string|\UnitEnum|null $navigationGroup = 'Operations';

    protected static ?int $navigationSort = 6;

    protected static ?string $modelLabel = 'Incident Status';

    public static function form(Schema $schema): Schema
    {
        return IncidentStatusOptionForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return IncidentStatusOptionsTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListIncidentStatusOptions::route('/'),
            'create' => CreateIncidentStatusOption::route('/create'),
            'view' => ViewIncidentStatusOption::route('/{record}'),
            'edit' => EditIncidentStatusOption::route('/{record}/edit'),
        ];
    }
}
