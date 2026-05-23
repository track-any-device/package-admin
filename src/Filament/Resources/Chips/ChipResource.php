<?php

namespace TrackAnyDevice\Admin\Filament\Resources\Chips;

use TrackAnyDevice\Admin\Filament\Resources\Chips\Pages\CreateChip;
use TrackAnyDevice\Admin\Filament\Resources\Chips\Pages\EditChip;
use TrackAnyDevice\Admin\Filament\Resources\Chips\Pages\ListChips;
use TrackAnyDevice\Admin\Filament\Resources\Chips\Pages\ViewChip;
use TrackAnyDevice\Admin\Filament\Resources\Chips\Schemas\ChipForm;
use TrackAnyDevice\Admin\Filament\Resources\Chips\Tables\ChipsTable;
use TrackAnyDevice\Core\Models\Chip;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class ChipResource extends Resource
{
    protected static ?string $model = Chip::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedCpuChip;

    protected static string|\UnitEnum|null $navigationGroup = 'Catalogue';

    public static function form(Schema $schema): Schema
    {
        return ChipForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return ChipsTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListChips::route('/'),
            'create' => CreateChip::route('/create'),
            'view' => ViewChip::route('/{record}'),
            'edit' => EditChip::route('/{record}/edit'),
        ];
    }
}
