<?php

namespace TrackAnyDevice\Admin\Filament\Resources\Cms;

use TrackAnyDevice\Admin\Filament\Resources\Cms\IndustryResource\Pages\CreateIndustry;
use TrackAnyDevice\Admin\Filament\Resources\Cms\IndustryResource\Pages\EditIndustry;
use TrackAnyDevice\Admin\Filament\Resources\Cms\IndustryResource\Pages\ListIndustries;
use TrackAnyDevice\Admin\Filament\Resources\Cms\PageResource\RelationManagers\SectionsRelationManager;
use TrackAnyDevice\Core\Models\Industry;
use BackedEnum;
use Filament\Actions\EditAction;
use Filament\Forms\Components\ColorPicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Support\Str;

class IndustryResource extends Resource
{
    protected static ?string $model = Industry::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedBuildingOffice2;

    protected static string|\UnitEnum|null $navigationGroup = 'CMS';

    protected static ?string $navigationLabel = 'Industries';

    protected static ?int $navigationSort = 3;

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('Industry')
                ->columns(2)
                ->schema([
                    TextInput::make('title')
                        ->required()
                        ->maxLength(100)
                        ->live(onBlur: true)
                        ->afterStateUpdated(fn ($state, callable $set) => $set('slug', Str::slug($state))),

                    TextInput::make('slug')
                        ->required()
                        ->maxLength(100)
                        ->unique(ignoreRecord: true),

                    Textarea::make('description')->rows(2)->columnSpanFull(),

                    Select::make('icon_name')
                        ->label('Icon (lucide-react)')
                        ->searchable()
                        ->options([
                            'Building2' => 'Building2', 'Factory' => 'Factory', 'Ship' => 'Ship',
                            'Truck' => 'Truck', 'Plane' => 'Plane', 'Tractor' => 'Tractor',
                            'HardHat' => 'HardHat', 'Stethoscope' => 'Stethoscope', 'GraduationCap' => 'GraduationCap',
                            'ShoppingBag' => 'ShoppingBag', 'Landmark' => 'Landmark', 'Wheat' => 'Wheat',
                        ]),

                    ColorPicker::make('color')->label('Accent color'),

                    FileUpload::make('hero_image')->image()->directory('cms/industries')->columnSpanFull(),

                    TextInput::make('sort_order')->numeric()->default(0),

                    Toggle::make('is_active')->default(true),
                    Toggle::make('is_featured')->label('Featured (shows on homepage)'),
                ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->reorderable('sort_order')
            ->defaultSort('sort_order')
            ->columns([
                TextColumn::make('sort_order')->label('#')->sortable(),
                ImageColumn::make('hero_image')->label('')->size(40)->square(),
                TextColumn::make('title')->searchable()->sortable(),
                TextColumn::make('icon_name')->label('Icon')->color('gray'),
                TextColumn::make('sections_count')->counts('allSections')->label('Sections')->badge(),
                IconColumn::make('is_featured')->boolean(),
                IconColumn::make('is_active')->boolean(),
            ])
            ->recordActions([EditAction::make()]);
    }

    public static function getRelations(): array
    {
        return [SectionsRelationManager::class];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListIndustries::route('/'),
            'create' => CreateIndustry::route('/create'),
            'edit' => EditIndustry::route('/{record}/edit'),
        ];
    }
}
