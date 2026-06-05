<?php

namespace TrackAnyDevice\Admin\Filament\Resources\IncidentPriorityOptions;

use TrackAnyDevice\Admin\Filament\Resources\IncidentPriorityOptions\Pages\CreateIncidentPriorityOption;
use TrackAnyDevice\Admin\Filament\Resources\IncidentPriorityOptions\Pages\EditIncidentPriorityOption;
use TrackAnyDevice\Admin\Filament\Resources\IncidentPriorityOptions\Pages\ListIncidentPriorityOptions;
use TrackAnyDevice\Admin\Filament\Resources\IncidentPriorityOptions\Pages\ViewIncidentPriorityOption;
use TrackAnyDevice\Admin\Filament\Resources\IncidentPriorityOptions\Schemas\IncidentPriorityOptionForm;
use TrackAnyDevice\Admin\Filament\Resources\IncidentPriorityOptions\Tables\IncidentPriorityOptionsTable;
use TrackAnyDevice\Core\Models\IncidentPriorityOption;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use TrackAnyDevice\Admin\Concerns\HasDepartmentAccess;
use TrackAnyDevice\Core\Enums\StaffDepartment;

class IncidentPriorityOptionResource extends Resource
{
    use HasDepartmentAccess;
    protected static ?string $model = IncidentPriorityOption::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedFlag;

    protected static string|\UnitEnum|null $navigationGroup = 'Operations';

    protected static ?int $navigationSort = 5;

    protected static ?string $modelLabel = 'Incident Priority';

    public static function form(Schema $schema): Schema
    {
        return IncidentPriorityOptionForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return IncidentPriorityOptionsTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListIncidentPriorityOptions::route('/'),
            'create' => CreateIncidentPriorityOption::route('/create'),
            'view' => ViewIncidentPriorityOption::route('/{record}'),
            'edit' => EditIncidentPriorityOption::route('/{record}/edit'),
        ];
    }

    protected static function getAllowedDepartments(): array
    {
        return [StaffDepartment::CoreTeam];
    }
}
