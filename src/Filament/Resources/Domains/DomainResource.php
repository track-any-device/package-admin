<?php

namespace TrackAnyDevice\Admin\Filament\Resources\Domains;

use TrackAnyDevice\Admin\Filament\Resources\Domains\Pages\CreateDomain;
use TrackAnyDevice\Admin\Filament\Resources\Domains\Pages\EditDomain;
use TrackAnyDevice\Admin\Filament\Resources\Domains\Pages\ListDomains;
use TrackAnyDevice\Admin\Filament\Resources\Domains\Pages\ViewDomain;
use TrackAnyDevice\Admin\Filament\Resources\Domains\Schemas\DomainForm;
use TrackAnyDevice\Admin\Filament\Resources\Domains\Tables\DomainsTable;
use TrackAnyDevice\Core\Models\Domain;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class DomainResource extends Resource
{
    protected static ?string $model = Domain::class;

    protected static ?string $slug = 'tenant-domains';

    protected static ?string $recordTitleAttribute = 'domain';

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedGlobeAlt;

    protected static string|\UnitEnum|null $navigationGroup = 'Administration';

    protected static ?int $navigationSort = 1;

    protected static ?string $navigationLabel = 'Tenant Domains';

    public static function form(Schema $schema): Schema
    {
        return DomainForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return DomainsTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListDomains::route('/'),
            'create' => CreateDomain::route('/create'),
            'view' => ViewDomain::route('/{record}'),
            'edit' => EditDomain::route('/{record}/edit'),
        ];
    }
}
