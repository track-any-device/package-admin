<?php

namespace TrackAnyDevice\Admin\Filament\Resources\OAuthClients;

use TrackAnyDevice\Admin\Filament\Resources\OAuthClients\Pages\CreateOAuthClient;
use TrackAnyDevice\Admin\Filament\Resources\OAuthClients\Pages\EditOAuthClient;
use TrackAnyDevice\Admin\Filament\Resources\OAuthClients\Pages\ListOAuthClients;
use TrackAnyDevice\Admin\Filament\Resources\OAuthClients\Pages\ViewOAuthClient;
use TrackAnyDevice\Admin\Filament\Resources\OAuthClients\Schemas\OAuthClientForm;
use TrackAnyDevice\Admin\Filament\Resources\OAuthClients\Tables\OAuthClientsTable;
use TrackAnyDevice\Admin\Models\OAuthClient;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use TrackAnyDevice\Admin\Concerns\HasDepartmentAccess;
use TrackAnyDevice\Core\Enums\StaffDepartment;

class OAuthClientResource extends Resource
{
    use HasDepartmentAccess;

    protected static ?string $model = OAuthClient::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedKey;

    protected static string|\UnitEnum|null $navigationGroup = 'System';

    protected static ?string $navigationLabel = 'OAuth Clients';

    protected static ?string $modelLabel = 'OAuth Client';

    protected static ?string $pluralModelLabel = 'OAuth Clients';

    protected static ?int $navigationSort = 3;

    public static function form(Schema $schema): Schema
    {
        return OAuthClientForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return OAuthClientsTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [];
    }

    /** @return list<StaffDepartment> */
    public static function getAllowedDepartments(): array
    {
        return [StaffDepartment::CoreTeam];
    }

    public static function getPages(): array
    {
        return [
            'index'  => ListOAuthClients::route('/'),
            'create' => CreateOAuthClient::route('/create'),
            'view'   => ViewOAuthClient::route('/{record}'),
            'edit'   => EditOAuthClient::route('/{record}/edit'),
        ];
    }
}
