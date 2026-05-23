<?php

namespace TrackAnyDevice\Admin\Filament\Resources\Countries;

use TrackAnyDevice\Admin\Filament\Resources\Countries\Pages\CreateCountry;
use TrackAnyDevice\Admin\Filament\Resources\Countries\Pages\EditCountry;
use TrackAnyDevice\Admin\Filament\Resources\Countries\Pages\ListCountries;
use TrackAnyDevice\Admin\Filament\Resources\Countries\Pages\ViewCountry;
use TrackAnyDevice\Admin\Filament\Resources\Countries\Schemas\CountryForm;
use TrackAnyDevice\Admin\Filament\Resources\Countries\Tables\CountriesTable;
use TrackAnyDevice\Core\Models\Country;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class CountryResource extends Resource
{
    protected static ?string $model = Country::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedGlobeAlt;

    protected static string|\UnitEnum|null $navigationGroup = 'Catalogue';

    public static function form(Schema $schema): Schema
    {
        return CountryForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return CountriesTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListCountries::route('/'),
            'create' => CreateCountry::route('/create'),
            'view' => ViewCountry::route('/{record}'),
            'edit' => EditCountry::route('/{record}/edit'),
        ];
    }
}
