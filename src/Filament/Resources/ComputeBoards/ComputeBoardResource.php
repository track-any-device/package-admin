<?php

namespace TrackAnyDevice\Admin\Filament\Resources\ComputeBoards;

use TrackAnyDevice\Admin\Filament\Resources\ComputeBoards\Pages\CreateComputeBoard;
use TrackAnyDevice\Admin\Filament\Resources\ComputeBoards\Pages\EditComputeBoard;
use TrackAnyDevice\Admin\Filament\Resources\ComputeBoards\Pages\ListComputeBoards;
use TrackAnyDevice\Admin\Filament\Resources\ComputeBoards\Pages\ViewComputeBoard;
use TrackAnyDevice\Admin\Filament\Resources\ComputeBoards\Schemas\ComputeBoardForm;
use TrackAnyDevice\Admin\Filament\Resources\ComputeBoards\Tables\ComputeBoardsTable;
use TrackAnyDevice\Core\Models\ComputeBoard;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use TrackAnyDevice\Admin\Concerns\HasDepartmentAccess;
use TrackAnyDevice\Core\Enums\StaffDepartment;

class ComputeBoardResource extends Resource
{
    use HasDepartmentAccess;

    protected static ?string $model = ComputeBoard::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedSquares2x2;

    protected static string|\UnitEnum|null $navigationGroup = 'Engineering';

    public static function form(Schema $schema): Schema
    {
        return ComputeBoardForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return ComputeBoardsTable::configure($table);
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
            'index' => ListComputeBoards::route('/'),
            'create' => CreateComputeBoard::route('/create'),
            'view' => ViewComputeBoard::route('/{record}'),
            'edit' => EditComputeBoard::route('/{record}/edit'),
        ];
    }
}
