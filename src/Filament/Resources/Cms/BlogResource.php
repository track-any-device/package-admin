<?php

namespace TrackAnyDevice\Admin\Filament\Resources\Cms;

use TrackAnyDevice\Admin\Filament\Resources\Cms\BlogResource\Pages\CreateBlog;
use TrackAnyDevice\Admin\Filament\Resources\Cms\BlogResource\Pages\EditBlog;
use TrackAnyDevice\Admin\Filament\Resources\Cms\BlogResource\Pages\ListBlogs;
use TrackAnyDevice\Admin\Filament\Resources\Cms\PageResource\RelationManagers\SectionsRelationManager;
use TrackAnyDevice\Core\Models\Blog;
use BackedEnum;
use Filament\Actions\EditAction;
use Filament\Forms\Components\DateTimePicker;
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

class BlogResource extends Resource
{
    protected static ?string $model = Blog::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedNewspaper;

    protected static string|\UnitEnum|null $navigationGroup = 'CMS';

    protected static ?string $navigationLabel = 'Blog';

    protected static ?int $navigationSort = 5;

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('Post')
                ->columns(2)
                ->schema([
                    TextInput::make('title')
                        ->required()
                        ->maxLength(200)
                        ->live(onBlur: true)
                        ->afterStateUpdated(fn ($state, callable $set) => $set('slug', Str::slug($state))),

                    TextInput::make('slug')
                        ->required()
                        ->maxLength(200)
                        ->unique(ignoreRecord: true),

                    Textarea::make('excerpt')
                        ->rows(3)
                        ->maxLength(500)
                        ->columnSpanFull()
                        ->helperText('One-paragraph summary shown in listing cards and meta description.'),

                    FileUpload::make('cover_image')
                        ->image()
                        ->directory('cms/blogs')
                        ->columnSpanFull(),

                    Select::make('author_id')
                        ->label('Author')
                        ->relationship('author', 'name')
                        ->searchable()
                        ->preload()
                        ->nullable(),

                    DateTimePicker::make('published_at')
                        ->label('Publish at')
                        ->helperText('Leave empty to keep as draft. A future date schedules publication.'),

                    TextInput::make('sort_order')->numeric()->default(0),

                    Toggle::make('is_active')->default(true),
                    Toggle::make('is_featured')->label('Featured (shows in Featured Blog Slider)'),
                ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->defaultSort('published_at', 'desc')
            ->columns([
                ImageColumn::make('cover_image')->label('')->size(40)->square(),
                TextColumn::make('title')->searchable()->sortable()->wrap()->limit(60),
                TextColumn::make('author.name')->label('Author')->color('gray'),
                TextColumn::make('sections_count')->counts('allSections')->label('Sections')->badge(),
                TextColumn::make('published_at')->dateTime('d M Y')->sortable(),
                IconColumn::make('is_featured')->boolean()->label('Featured'),
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
            'index' => ListBlogs::route('/'),
            'create' => CreateBlog::route('/create'),
            'edit' => EditBlog::route('/{record}/edit'),
        ];
    }
}
