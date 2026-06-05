<?php

namespace TrackAnyDevice\Admin\Filament\Resources\Cms;

use TrackAnyDevice\Admin\Filament\Resources\Cms\PageResource\Pages\CreatePage;
use TrackAnyDevice\Admin\Filament\Resources\Cms\PageResource\Pages\EditPage;
use TrackAnyDevice\Admin\Filament\Resources\Cms\PageResource\Pages\ListPages;
use TrackAnyDevice\Admin\Filament\Resources\Cms\PageResource\Pages\ViewPage;
use TrackAnyDevice\Admin\Filament\Resources\Cms\PageResource\RelationManagers\SectionsRelationManager;
use TrackAnyDevice\Core\Models\Page;
use BackedEnum;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Support\Str;
use TrackAnyDevice\Admin\Concerns\HasDepartmentAccess;
use TrackAnyDevice\Core\Enums\StaffDepartment;

class PageResource extends Resource
{
    use HasDepartmentAccess;
    protected static ?string $model = Page::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedDocumentText;

    protected static string|\UnitEnum|null $navigationGroup = 'Marketing';

    protected static ?string $navigationLabel = 'Pages';

    protected static ?int $navigationSort = 4;

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
                ->unique(ignoreRecord: true)
                ->helperText('Used to load the page (e.g. "home", "about", "store")'),

            TextInput::make('meta_title')->maxLength(100)->label('Meta Title'),
            Textarea::make('meta_description')->rows(2)->label('Meta Description'),
            Toggle::make('is_active')->default(true),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('title')->searchable()->sortable(),
                TextColumn::make('slug')->copyable()->color('gray'),
                TextColumn::make('sections_count')
                    ->counts('sections')
                    ->label('Sections')
                    ->badge(),
                IconColumn::make('is_active')->boolean(),
                TextColumn::make('updated_at')->dateTime('d M Y, H:i')->sortable()->label('Last Updated'),
            ])
            ->recordActions([ViewAction::make(), EditAction::make()])
            ->defaultSort('title');
    }

    public static function getRelations(): array
    {
        return [SectionsRelationManager::class];
    }

    protected static function getAllowedDepartments(): array
    {
        return [StaffDepartment::Marketing];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListPages::route('/'),
            'create' => CreatePage::route('/create'),
            'view' => ViewPage::route('/{record}'),
            'edit' => EditPage::route('/{record}/edit'),
        ];
    }
}
