<?php

namespace TrackAnyDevice\Admin\Filament\Resources\Cms;

use TrackAnyDevice\Admin\Filament\Resources\Cms\PageResource\RelationManagers\SectionsRelationManager;
use TrackAnyDevice\Admin\Filament\Resources\Cms\SolutionResource\Pages\CreateSolution;
use TrackAnyDevice\Admin\Filament\Resources\Cms\SolutionResource\Pages\EditSolution;
use TrackAnyDevice\Admin\Filament\Resources\Cms\SolutionResource\Pages\ListSolutions;
use TrackAnyDevice\Core\Models\Solution;
use BackedEnum;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Support\Str;

class SolutionResource extends Resource
{
    protected static ?string $model = Solution::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedLightBulb;

    protected static string|\UnitEnum|null $navigationGroup = 'CMS';

    protected static ?string $navigationLabel = 'Solutions';

    protected static ?int $navigationSort = 2;

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            TextInput::make('title')
                ->required()
                ->maxLength(100)
                ->live(onBlur: true)
                ->afterStateUpdated(fn ($state, callable $set) => $set('slug', Str::slug($state))),

            TextInput::make('slug')
                ->required()
                ->maxLength(100)
                ->unique(ignoreRecord: true),

            Textarea::make('description')->rows(2),

            Select::make('icon_name')
                ->label('Icon (lucide-react)')
                ->searchable()
                ->options([
                    'Truck' => 'Truck', 'Package' => 'Package', 'Leaf' => 'Leaf',
                    'Droplets' => 'Droplets', 'Thermometer' => 'Thermometer',
                    'Building2' => 'Building2', 'MapPin' => 'MapPin',
                    'Factory' => 'Factory', 'Ship' => 'Ship', 'Zap' => 'Zap',
                    'Shield' => 'Shield', 'Radio' => 'Radio',
                ]),

            TextInput::make('gradient_from')->placeholder('blue-900')->default('blue-900'),
            TextInput::make('gradient_to')->placeholder('blue-700')->default('blue-700'),

            FileUpload::make('image_path')->image()->directory('solutions')->nullable(),

            TextInput::make('cta_label')->placeholder('Learn More'),
            TextInput::make('cta_href')->placeholder('/solutions/fleet'),

            TextInput::make('sort_order')->numeric()->default(0),
            Toggle::make('is_active')->default(true),
            Toggle::make('is_featured')->default(true)->helperText('Show on homepage'),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->reorderable('sort_order')
            ->defaultSort('sort_order')
            ->columns([
                TextColumn::make('sort_order')->label('#')->sortable(),
                ImageColumn::make('image_path')->label('')->size(40)->circular(),
                TextColumn::make('title')->searchable(),
                TextColumn::make('icon_name')->label('Icon')->color('gray'),
                IconColumn::make('is_featured')->label('Featured')->boolean(),
                IconColumn::make('is_active')->boolean(),
            ])
            ->recordActions([EditAction::make()])
            ->toolbarActions([BulkActionGroup::make([DeleteBulkAction::make()])]);
    }

    public static function getRelations(): array
    {
        return [SectionsRelationManager::class];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListSolutions::route('/'),
            'create' => CreateSolution::route('/create'),
            'edit' => EditSolution::route('/{record}/edit'),
        ];
    }
}
