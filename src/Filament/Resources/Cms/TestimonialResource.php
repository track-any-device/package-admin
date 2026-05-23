<?php

namespace TrackAnyDevice\Admin\Filament\Resources\Cms;

use TrackAnyDevice\Admin\Filament\Resources\Cms\TestimonialResource\Pages\ListTestimonials;
use TrackAnyDevice\Core\Models\Testimonial;
use BackedEnum;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\ColorPicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;

class TestimonialResource extends Resource
{
    protected static ?string $model = Testimonial::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedChatBubbleLeftRight;

    protected static string|\UnitEnum|null $navigationGroup = 'CMS';

    protected static ?string $navigationLabel = 'Testimonials';

    protected static ?int $navigationSort = 3;

    public static function getNavigationBadge(): ?string
    {
        $pending = Testimonial::where('is_approved', false)->count();

        return $pending > 0 ? (string) $pending : null;
    }

    public static function getNavigationBadgeColor(): ?string
    {
        return 'warning';
    }

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            TextInput::make('name')->required()->maxLength(100),
            TextInput::make('role')->maxLength(100)->placeholder('Fleet Manager'),
            TextInput::make('company')->maxLength(100),
            Textarea::make('quote')->required()->rows(3),
            FileUpload::make('avatar_path')->label('Avatar Photo')->image()->directory('testimonials')->nullable(),
            TextInput::make('avatar_initials')->label('Initials (fallback)')->maxLength(4)->placeholder('MR'),
            ColorPicker::make('avatar_color')->label('Avatar Background Color')->default('#2563eb'),
            Select::make('rating')
                ->options([1 => '★', 2 => '★★', 3 => '★★★', 4 => '★★★★', 5 => '★★★★★'])
                ->default(5)
                ->required(),
            TextInput::make('campaign')
                ->helperText('Group testimonials by campaign slug for bulk collection (e.g. fleet-2026)')
                ->nullable(),
            TextInput::make('sort_order')->numeric()->default(0),
            Toggle::make('is_featured')
                ->helperText('Only featured testimonials appear on the homepage')
                ->default(false),
            Toggle::make('is_approved')
                ->helperText('Unapproved testimonials are hidden from the site')
                ->default(true),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->defaultSort('sort_order')
            ->columns([
                TextColumn::make('name')->searchable()->sortable(),
                TextColumn::make('role')->color('gray'),
                TextColumn::make('campaign')->badge()->color('info'),
                TextColumn::make('rating')
                    ->formatStateUsing(fn ($state) => str_repeat('★', (int) $state)),
                IconColumn::make('is_featured')->label('Featured')->boolean(),
                IconColumn::make('is_approved')->label('Approved')->boolean(),
                TextColumn::make('created_at')->dateTime('d M Y')->sortable()->label('Submitted'),
            ])
            ->filters([
                TernaryFilter::make('is_featured')->label('Featured'),
                TernaryFilter::make('is_approved')->label('Approved'),
                SelectFilter::make('campaign')->options(
                    fn () => Testimonial::whereNotNull('campaign')
                        ->distinct()
                        ->pluck('campaign', 'campaign')
                        ->all()
                ),
            ])
            ->recordActions([EditAction::make()])
            ->toolbarActions([BulkActionGroup::make([DeleteBulkAction::make()])]);
    }

    public static function getPages(): array
    {
        return ['index' => ListTestimonials::route('/')];
    }
}
