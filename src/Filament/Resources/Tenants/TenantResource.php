<?php

namespace TrackAnyDevice\Admin\Filament\Resources\Tenants;

use TrackAnyDevice\Admin\Filament\Resources\Tenants\Pages\CreateTenant;
use TrackAnyDevice\Admin\Filament\Resources\Tenants\Pages\EditTenant;
use TrackAnyDevice\Admin\Filament\Resources\Tenants\Pages\ListTenants;
use TrackAnyDevice\Admin\Filament\Resources\Tenants\Pages\ViewTenant;
use TrackAnyDevice\Admin\Filament\Resources\Tenants\RelationManagers\DomainsRelationManager;
use TrackAnyDevice\Admin\Filament\Resources\Tenants\RelationManagers\MembersRelationManager;
use TrackAnyDevice\Admin\Filament\Resources\Tenants\RelationManagers\ScreensRelationManager;
use TrackAnyDevice\Admin\Filament\Resources\Tenants\RelationManagers\TenantApiKeysRelationManager;
use TrackAnyDevice\Admin\Filament\Resources\Tenants\Schemas\TenantForm;
use TrackAnyDevice\Admin\Filament\Resources\Tenants\Tables\TenantsTable;
use TrackAnyDevice\Core\Models\Tenant;
use TrackAnyDevice\Core\Models\TenantStatus;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class TenantResource extends Resource
{
    protected static ?string $model = Tenant::class;

    // Use 'organisations' slug to avoid Filament's reserved 'tenants' route keyword
    protected static ?string $slug = 'organisations';

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedBuildingOffice2;

    protected static string|\UnitEnum|null $navigationGroup = 'Access Control';

    protected static ?int $navigationSort = 2;

    public static function getNavigationBadge(): ?string
    {
        $pending = Tenant::where('status', TenantStatus::Pending)->count();

        return $pending > 0 ? (string) $pending : null;
    }

    public static function getNavigationBadgeColor(): ?string
    {
        return 'warning';
    }

    public static function form(Schema $schema): Schema
    {
        return TenantForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return TenantsTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            MembersRelationManager::class,
            DomainsRelationManager::class,
            ScreensRelationManager::class,
            TenantApiKeysRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListTenants::route('/'),
            'create' => CreateTenant::route('/create'),
            'view' => ViewTenant::route('/{record}'),
            'edit' => EditTenant::route('/{record}/edit'),
        ];
    }
}
