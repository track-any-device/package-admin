<?php

namespace TrackAnyDevice\Admin\Filament\Resources\Users;

use TrackAnyDevice\Admin\Filament\Resources\Users\Pages\CreateUser;
use TrackAnyDevice\Admin\Filament\Resources\Users\Pages\EditUser;
use TrackAnyDevice\Admin\Filament\Resources\Users\Pages\ListUsers;
use TrackAnyDevice\Admin\Filament\Resources\Users\Schemas\UserForm;
use TrackAnyDevice\Admin\Filament\Resources\Users\Tables\UsersTable;
use TrackAnyDevice\Admin\Concerns\HasDepartmentAccess;
use TrackAnyDevice\Core\Enums\StaffDepartment;
use TrackAnyDevice\Core\Models\User;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class UserResource extends Resource
{
    use HasDepartmentAccess;

    protected static ?string $model = User::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedUsers;

    protected static string|\UnitEnum|null $navigationGroup = 'System';

    protected static ?int $navigationSort = 1;

    public static function form(Schema $schema): Schema
    {
        return UserForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return UsersTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    protected static function getAllowedDepartments(): array
    {
        return [StaffDepartment::CoreTeam];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListUsers::route('/'),
            'create' => CreateUser::route('/create'),
            'edit' => EditUser::route('/{record}/edit'),
        ];
    }
}
