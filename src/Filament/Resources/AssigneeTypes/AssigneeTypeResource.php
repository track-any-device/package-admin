<?php

namespace TrackAnyDevice\Admin\Filament\Resources\AssigneeTypes;

use TrackAnyDevice\Admin\Filament\Resources\AssigneeTypes\Pages\CreateAssigneeType;
use TrackAnyDevice\Admin\Filament\Resources\AssigneeTypes\Pages\EditAssigneeType;
use TrackAnyDevice\Admin\Filament\Resources\AssigneeTypes\Pages\ListAssigneeTypes;
use TrackAnyDevice\Admin\Filament\Resources\AssigneeTypes\Pages\ViewAssigneeType;
use TrackAnyDevice\Admin\Filament\Resources\AssigneeTypes\Schemas\AssigneeTypeForm;
use TrackAnyDevice\Admin\Filament\Resources\AssigneeTypes\Tables\AssigneeTypesTable;
use TrackAnyDevice\Core\Models\AssigneeType;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class AssigneeTypeResource extends Resource
{
    protected static ?string $model = AssigneeType::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedTag;

    protected static string|\UnitEnum|null $navigationGroup = 'Assignees';

    protected static ?int $navigationSort = 2;

    public static function form(Schema $schema): Schema
    {
        return AssigneeTypeForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return AssigneeTypesTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListAssigneeTypes::route('/'),
            'create' => CreateAssigneeType::route('/create'),
            'view' => ViewAssigneeType::route('/{record}'),
            'edit' => EditAssigneeType::route('/{record}/edit'),
        ];
    }
}
