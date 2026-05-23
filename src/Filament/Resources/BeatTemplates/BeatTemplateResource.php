<?php

namespace TrackAnyDevice\Admin\Filament\Resources\BeatTemplates;

use TrackAnyDevice\Admin\Filament\Resources\BeatTemplates\Pages\CreateBeatTemplate;
use TrackAnyDevice\Admin\Filament\Resources\BeatTemplates\Pages\EditBeatTemplate;
use TrackAnyDevice\Admin\Filament\Resources\BeatTemplates\Pages\ListBeatTemplates;
use TrackAnyDevice\Admin\Filament\Resources\BeatTemplates\Schemas\BeatTemplateForm;
use TrackAnyDevice\Admin\Filament\Resources\BeatTemplates\Tables\BeatTemplatesTable;
use TrackAnyDevice\Core\Models\BeatTemplate;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class BeatTemplateResource extends Resource
{
    protected static ?string $model = BeatTemplate::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static string|\UnitEnum|null $navigationGroup = 'Operations';

    protected static ?int $navigationSort = 2;

    protected static ?string $recordTitleAttribute = 'name';

    public static function form(Schema $schema): Schema
    {
        return BeatTemplateForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return BeatTemplatesTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListBeatTemplates::route('/'),
            'create' => CreateBeatTemplate::route('/create'),
            'edit' => EditBeatTemplate::route('/{record}/edit'),
        ];
    }
}
