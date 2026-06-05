<?php

namespace TrackAnyDevice\Admin\Filament\Resources\Beats;

use TrackAnyDevice\Admin\Filament\Resources\Beats\Pages\CreateBeat;
use TrackAnyDevice\Admin\Filament\Resources\Beats\Pages\EditBeat;
use TrackAnyDevice\Admin\Filament\Resources\Beats\Pages\ListBeats;
use TrackAnyDevice\Admin\Filament\Resources\Beats\Pages\ViewBeat;
use TrackAnyDevice\Admin\Filament\Resources\Beats\RelationManagers\BeatAssignmentsRelationManager;
use TrackAnyDevice\Admin\Filament\Resources\Beats\Schemas\BeatForm;
use TrackAnyDevice\Admin\Filament\Resources\Beats\Tables\BeatsTable;
use TrackAnyDevice\Core\Models\Beat;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use TrackAnyDevice\Admin\Concerns\HasDepartmentAccess;
use TrackAnyDevice\Core\Enums\StaffDepartment;

class BeatResource extends Resource
{
    use HasDepartmentAccess;
    protected static ?string $model = Beat::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedMapPin;

    protected static string|\UnitEnum|null $navigationGroup = 'Operations';

    protected static ?int $navigationSort = 1;

    public static function form(Schema $schema): Schema
    {
        return BeatForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return BeatsTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            BeatAssignmentsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListBeats::route('/'),
            'create' => CreateBeat::route('/create'),
            'view' => ViewBeat::route('/{record}'),
            'edit' => EditBeat::route('/{record}/edit'),
        ];
    }

    protected static function getAllowedDepartments(): array
    {
        return [StaffDepartment::CoreTeam];
    }
}
