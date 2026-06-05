<?php

namespace TrackAnyDevice\Admin\Filament\Resources\Assignees;

use TrackAnyDevice\Admin\Filament\Resources\Assignees\Pages\CreateAssignee;
use TrackAnyDevice\Admin\Filament\Resources\Assignees\Pages\EditAssignee;
use TrackAnyDevice\Admin\Filament\Resources\Assignees\Pages\ListAssignees;
use TrackAnyDevice\Admin\Filament\Resources\Assignees\Pages\ViewAssignee;
use TrackAnyDevice\Admin\Filament\Resources\Assignees\Schemas\AssigneeForm;
use TrackAnyDevice\Admin\Filament\Resources\Assignees\Tables\AssigneesTable;
use TrackAnyDevice\Core\Models\Assignee;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use TrackAnyDevice\Admin\Concerns\HasDepartmentAccess;
use TrackAnyDevice\Core\Enums\StaffDepartment;

class AssigneeResource extends Resource
{
    use HasDepartmentAccess;
    protected static ?string $model = Assignee::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedUsers;

    protected static string|\UnitEnum|null $navigationGroup = 'Operations';

    protected static ?int $navigationSort = 1;

    public static function form(Schema $schema): Schema
    {
        return AssigneeForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return AssigneesTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListAssignees::route('/'),
            'create' => CreateAssignee::route('/create'),
            'view' => ViewAssignee::route('/{record}'),
            'edit' => EditAssignee::route('/{record}/edit'),
        ];
    }

    protected static function getAllowedDepartments(): array
    {
        return [StaffDepartment::CoreTeam];
    }
}
