<?php

namespace TrackAnyDevice\Admin\Filament\Resources\Cms\PageResource\RelationManagers;

use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\ColorPicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Components\Fieldset;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section as SchemaSection;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

/**
 * Universal sections manager. Works on any model that exposes a `sections`
 * morphMany relation — Page, Solution, Blog, Industry, DeviceType.
 *
 * Section type catalogue (12 components):
 *   1. hero                       size: full|half|third + bg modes + buttons[]
 *   2. cards_grid                 generic icon|image|stat|minimal grid
 *   3. featured_solutions_grid    pulls from Solution::featured()
 *   4. featured_products_grid     pulls from DeviceType::featured()
 *   5. featured_blog_slider       featured + vertical scroll-snap list
 *   6. solutions_with_filter      full solutions list + filters
 *   7. products_with_filter       full products list + filters
 *   8. blogs_listing              paginated blog list + filters
 *   9. contact_form               configurable form fields + contact info
 *  10. text_section               TipTap rich-text body
 *  11. cta_section                bg + title + subtitle + buttons[]
 *  12. banner_5050                image + content side-by-side
 */
class SectionsRelationManager extends RelationManager
{
    protected static string $relationship = 'sections';

    public const TYPES = [
        'hero' => 'Hero',
        'cards_grid' => 'Cards Grid',
        'featured_solutions_grid' => 'Featured Solutions Grid',
        'featured_products_grid' => 'Featured Products Grid',
        'featured_blog_slider' => 'Featured Blog Slider',
        'solutions_with_filter' => 'Solutions List with Filters',
        'products_with_filter' => 'Products List with Filters',
        'blogs_listing' => 'Blogs Listing',
        'contact_form' => 'Contact Form',
        'text_section' => 'Text Section',
        'cta_section' => 'CTA Section',
        'banner_5050' => '50/50 Banner',
    ];

    public function form(Schema $schema): Schema
    {
        return $schema->components([
            Grid::make(2)->schema([
                Select::make('type')
                    ->options(self::TYPES)
                    ->required()
                    ->live(),
                TextInput::make('identifier')
                    ->label('Anchor ID')
                    ->placeholder('explore-more')
                    ->helperText('Used as the HTML id="" for scroll targets.'),
                TextInput::make('sort_order')->numeric()->default(0),
                Toggle::make('is_active')->default(true),
            ]),

            SchemaSection::make('Hero')
                ->visible(fn ($get) => $get('type') === 'hero')
                ->schema($this->heroFields()),

            SchemaSection::make('Cards Grid')
                ->visible(fn ($get) => $get('type') === 'cards_grid')
                ->schema($this->cardsGridFields()),

            SchemaSection::make('Featured Solutions Grid')
                ->visible(fn ($get) => $get('type') === 'featured_solutions_grid')
                ->schema($this->featuredGridFields('Trusted Solutions')),

            SchemaSection::make('Featured Products Grid')
                ->visible(fn ($get) => $get('type') === 'featured_products_grid')
                ->schema($this->featuredGridFields('Powerful Devices')),

            SchemaSection::make('Featured Blog Slider')
                ->visible(fn ($get) => $get('type') === 'featured_blog_slider')
                ->schema($this->featuredBlogSliderFields()),

            SchemaSection::make('Solutions List with Filters')
                ->visible(fn ($get) => $get('type') === 'solutions_with_filter')
                ->schema($this->solutionsFilterFields()),

            SchemaSection::make('Products List with Filters')
                ->visible(fn ($get) => $get('type') === 'products_with_filter')
                ->schema($this->productsFilterFields()),

            SchemaSection::make('Blogs Listing')
                ->visible(fn ($get) => $get('type') === 'blogs_listing')
                ->schema($this->blogsListingFields()),

            SchemaSection::make('Contact Form')
                ->visible(fn ($get) => $get('type') === 'contact_form')
                ->schema($this->contactFormFields()),

            SchemaSection::make('Text Section')
                ->visible(fn ($get) => $get('type') === 'text_section')
                ->schema($this->textSectionFields()),

            SchemaSection::make('CTA Section')
                ->visible(fn ($get) => $get('type') === 'cta_section')
                ->schema($this->ctaSectionFields()),

            SchemaSection::make('50/50 Banner')
                ->visible(fn ($get) => $get('type') === 'banner_5050')
                ->schema($this->banner5050Fields()),
        ]);
    }

    // ── Shared field builders ──────────────────────────────────────────────

    private function buttonsRepeater(string $label = 'Buttons', int $maxItems = 3): Repeater
    {
        return Repeater::make('content.buttons')
            ->label($label)
            ->schema([
                TextInput::make('label')->required()->placeholder('Get Started'),
                TextInput::make('link')->required()->placeholder('/contact or https://...'),
                TextInput::make('icon')->placeholder('lucide icon, e.g. ArrowRight'),
                Select::make('variant')
                    ->options([
                        'primary' => 'Primary',
                        'secondary' => 'Secondary',
                        'outline' => 'Outline',
                        'ghost' => 'Ghost',
                        'destructive' => 'Destructive',
                    ])
                    ->default('primary'),
            ])
            ->columns(4)
            ->collapsible()
            ->reorderable()
            ->maxItems($maxItems)
            ->defaultItems(0);
    }

    private function backgroundFieldset(bool $includeMap = true): Fieldset
    {
        $kinds = [
            'image' => 'Image',
            'color' => 'Solid Color (token)',
            'gradient' => 'Gradient',
            'video' => 'Video',
        ];
        if ($includeMap) {
            $kinds['map'] = 'Map (live markers)';
        }

        return Fieldset::make('Background')
            ->schema([
                Select::make('content.bg.kind')
                    ->label('Background type')
                    ->options($kinds)
                    ->default('color')
                    ->live(),

                FileUpload::make('content.bg.image_path')
                    ->label('Image')
                    ->image()
                    ->directory('cms/hero')
                    ->visible(fn ($get) => $get('content.bg.kind') === 'image'),

                TextInput::make('content.bg.video_url')
                    ->label('Video URL (mp4)')
                    ->placeholder('https://...')
                    ->visible(fn ($get) => $get('content.bg.kind') === 'video'),

                FileUpload::make('content.bg.video_poster')
                    ->label('Video poster image')
                    ->image()
                    ->directory('cms/hero')
                    ->visible(fn ($get) => $get('content.bg.kind') === 'video'),

                Select::make('content.bg.color_token')
                    ->label('Color token')
                    ->options([
                        'primary' => 'Primary',
                        'accent' => 'Accent',
                        'muted' => 'Muted',
                        'surface' => 'Surface',
                        'card' => 'Card',
                        'secondary' => 'Secondary',
                    ])
                    ->default('primary')
                    ->visible(fn ($get) => $get('content.bg.kind') === 'color'),

                ColorPicker::make('content.bg.gradient_from')
                    ->label('Gradient from')
                    ->visible(fn ($get) => $get('content.bg.kind') === 'gradient'),
                ColorPicker::make('content.bg.gradient_to')
                    ->label('Gradient to')
                    ->visible(fn ($get) => $get('content.bg.kind') === 'gradient'),
                Select::make('content.bg.gradient_direction')
                    ->label('Gradient direction')
                    ->options([
                        'to-r' => 'Left → Right',
                        'to-l' => 'Right → Left',
                        'to-b' => 'Top → Bottom',
                        'to-t' => 'Bottom → Top',
                        'to-br' => 'Top-left → Bottom-right',
                        'to-bl' => 'Top-right → Bottom-left',
                    ])
                    ->default('to-br')
                    ->visible(fn ($get) => $get('content.bg.kind') === 'gradient'),

                TextInput::make('content.bg.overlay_alpha')
                    ->label('Overlay darkness (0–100)')
                    ->numeric()
                    ->minValue(0)
                    ->maxValue(100)
                    ->default(30)
                    ->visible(fn ($get) => in_array($get('content.bg.kind'), ['image', 'video'], true)),

                TextInput::make('content.bg.map_center_lat')
                    ->label('Map center latitude')
                    ->numeric()
                    ->default(31.5204)
                    ->visible(fn ($get) => $get('content.bg.kind') === 'map'),
                TextInput::make('content.bg.map_center_lng')
                    ->label('Map center longitude')
                    ->numeric()
                    ->default(74.3587)
                    ->visible(fn ($get) => $get('content.bg.kind') === 'map'),
                TextInput::make('content.bg.map_zoom')
                    ->label('Map zoom')
                    ->numeric()
                    ->minValue(1)
                    ->maxValue(18)
                    ->default(6)
                    ->visible(fn ($get) => $get('content.bg.kind') === 'map'),
                Toggle::make('content.bg.map_show_devices')
                    ->label('Show pulsing device markers')
                    ->default(true)
                    ->visible(fn ($get) => $get('content.bg.kind') === 'map'),
            ])
            ->columns(2);
    }

    // ── Per-type field schemas ─────────────────────────────────────────────

    private function heroFields(): array
    {
        return [
            Select::make('content.size')
                ->label('Size')
                ->options([
                    'full' => 'Full page (100vh)',
                    'half' => 'Half page (50vh)',
                    'third' => 'One-third page (33vh)',
                ])
                ->default('half')
                ->required(),

            Select::make('content.alignment')
                ->label('Content alignment')
                ->options(['left' => 'Left', 'center' => 'Center'])
                ->default('center'),

            TextInput::make('content.eyebrow')->label('Eyebrow (small label above title)'),
            TextInput::make('content.title')->label('Title')->required(),
            TextInput::make('content.title_highlight')->label('Highlighted word(s) in title'),
            Textarea::make('content.subtitle')->label('Subtitle')->rows(2),
            Textarea::make('content.body')->label('Body')->rows(3),

            $this->backgroundFieldset(includeMap: true),
            $this->buttonsRepeater('CTA Buttons', maxItems: 3),
        ];
    }

    private function cardsGridFields(): array
    {
        return [
            TextInput::make('content.eyebrow'),
            TextInput::make('content.title')->required(),
            Textarea::make('content.subtitle')->rows(2),
            Select::make('content.columns')
                ->options([2 => '2 columns', 3 => '3 columns', 4 => '4 columns'])
                ->default(3),
            Select::make('content.card_style')
                ->options([
                    'icon' => 'Icon card',
                    'image' => 'Image card',
                    'stat' => 'Stat card (big number)',
                    'minimal' => 'Minimal',
                ])
                ->default('icon')
                ->required(),
            Repeater::make('content.items')
                ->label('Cards')
                ->schema([
                    TextInput::make('icon')->placeholder('lucide icon name'),
                    FileUpload::make('image')->image()->directory('cms/cards')->nullable(),
                    TextInput::make('title')->required(),
                    TextInput::make('value')->placeholder('Stat value (only for stat cards)'),
                    Textarea::make('description')->rows(2),
                    TextInput::make('link')->placeholder('/path or https://...'),
                    TextInput::make('link_label')->placeholder('Learn more'),
                ])
                ->columns(2)
                ->collapsible()
                ->reorderable(),
        ];
    }

    private function featuredGridFields(string $defaultTitle): array
    {
        return [
            TextInput::make('content.eyebrow'),
            TextInput::make('content.title')->required()->default($defaultTitle),
            Textarea::make('content.subtitle')->rows(2),
            Select::make('content.columns')
                ->options([2 => '2 cols', 3 => '3 cols', 4 => '4 cols'])
                ->default(3),
            TextInput::make('content.max_items')->numeric()->default(6),
            Toggle::make('content.show_buttons_on_cards')->label('Show CTA button on each card')->default(true),
            TextInput::make('content.card_button_label')->default('Learn more'),
            $this->buttonsRepeater('Section-level Buttons (below grid)', maxItems: 2),
        ];
    }

    private function featuredBlogSliderFields(): array
    {
        return [
            TextInput::make('content.eyebrow'),
            TextInput::make('content.title')->required()->default('From the blog'),
            Textarea::make('content.subtitle')->rows(2),
            TextInput::make('content.featured_blog_slug')
                ->label('Featured post slug (leave empty = newest)')
                ->placeholder('e.g. tad101-introduction'),
            TextInput::make('content.list_count')
                ->numeric()
                ->minValue(2)
                ->maxValue(10)
                ->default(5)
                ->helperText('How many recent posts in the side list.'),
            $this->buttonsRepeater('Buttons', maxItems: 2),
        ];
    }

    private function solutionsFilterFields(): array
    {
        return [
            TextInput::make('content.title')->required()->default('All Solutions'),
            Textarea::make('content.subtitle')->rows(2),
            TextInput::make('content.items_per_page')->numeric()->default(12),
            Repeater::make('content.filters.categories')
                ->label('Category filter options')
                ->schema([
                    TextInput::make('label')->required(),
                    TextInput::make('value')->required(),
                ])
                ->columns(2)
                ->collapsible()
                ->defaultItems(0),
            Repeater::make('content.filters.industries')
                ->label('Industry filter options')
                ->schema([
                    TextInput::make('label')->required(),
                    TextInput::make('value')->required(),
                ])
                ->columns(2)
                ->collapsible()
                ->defaultItems(0),
        ];
    }

    private function productsFilterFields(): array
    {
        return [
            TextInput::make('content.title')->required()->default('Shop Tracking Devices'),
            Textarea::make('content.subtitle')->rows(2),
            TextInput::make('content.cta_order_href')->default('/store'),
            TextInput::make('content.items_per_page')->numeric()->default(12),
            Repeater::make('content.categories')
                ->label('Category filter options')
                ->schema([
                    TextInput::make('label')->required(),
                    TextInput::make('value')->required(),
                ])
                ->columns(2)
                ->collapsible()
                ->defaultItems(0),
            Repeater::make('content.use_cases')
                ->label('Use-case filter options')
                ->schema([
                    TextInput::make('label')->required(),
                    TextInput::make('value')->required(),
                ])
                ->columns(2)
                ->collapsible()
                ->defaultItems(0),
            Repeater::make('content.connectivity')
                ->label('Connectivity filter options')
                ->schema([
                    TextInput::make('label')->required(),
                    TextInput::make('value')->required(),
                ])
                ->columns(2)
                ->collapsible()
                ->defaultItems(0),
        ];
    }

    private function blogsListingFields(): array
    {
        return [
            TextInput::make('content.title')->required()->default('Latest from the blog'),
            Textarea::make('content.subtitle')->rows(2),
            TextInput::make('content.items_per_page')->numeric()->default(12),
            Toggle::make('content.show_tag_filter')->label('Show tag filter')->default(true),
            Toggle::make('content.show_author_filter')->label('Show author filter')->default(false),
        ];
    }

    private function contactFormFields(): array
    {
        return [
            TextInput::make('content.title')->required()->default('Get in touch'),
            Textarea::make('content.subtitle')->rows(2),
            Select::make('content.fields')
                ->label('Form fields to show')
                ->multiple()
                ->options([
                    'name' => 'Name',
                    'email' => 'Email',
                    'phone' => 'Phone',
                    'company' => 'Company',
                    'subject' => 'Subject',
                    'message' => 'Message',
                ])
                ->default(['name', 'email', 'phone', 'message'])
                ->required(),
            TextInput::make('content.submit_label')->default('Send message'),
            Textarea::make('content.success_message')
                ->rows(2)
                ->default("Thanks — we'll reply within one business day."),
            Fieldset::make('Contact info shown alongside the form')
                ->schema([
                    TextInput::make('content.contact_info.phone'),
                    TextInput::make('content.contact_info.email'),
                    Textarea::make('content.contact_info.address')->rows(2),
                ])->columns(1),
        ];
    }

    private function textSectionFields(): array
    {
        return [
            TextInput::make('content.eyebrow'),
            TextInput::make('content.title'),
            Select::make('content.alignment')
                ->options(['left' => 'Left', 'center' => 'Center'])
                ->default('left'),
            Select::make('content.max_width')
                ->options([
                    'narrow' => 'Narrow (prose)',
                    'medium' => 'Medium',
                    'wide' => 'Wide',
                    'full' => 'Full',
                ])
                ->default('narrow'),
            RichEditor::make('content.body_html')
                ->label('Body')
                ->toolbarButtons([
                    'bold', 'italic', 'strike', 'link', 'h2', 'h3',
                    'bulletList', 'orderedList', 'blockquote', 'codeBlock', 'undo', 'redo',
                ])
                ->columnSpanFull(),
        ];
    }

    private function ctaSectionFields(): array
    {
        return [
            TextInput::make('content.eyebrow'),
            TextInput::make('content.title')->required(),
            Textarea::make('content.subtitle')->rows(2),
            Select::make('content.alignment')
                ->options(['left' => 'Left', 'center' => 'Center'])
                ->default('center'),
            $this->backgroundFieldset(includeMap: false),
            $this->buttonsRepeater('CTA Buttons', maxItems: 3),
        ];
    }

    private function banner5050Fields(): array
    {
        return [
            FileUpload::make('content.image')
                ->image()
                ->directory('cms/banners')
                ->required(),
            Select::make('content.image_position')
                ->options(['left' => 'Image left', 'right' => 'Image right'])
                ->default('left'),
            TextInput::make('content.eyebrow'),
            TextInput::make('content.title')->required(),
            Textarea::make('content.body')->rows(3),
            Repeater::make('content.bullets')
                ->label('Bullets')
                ->schema([
                    TextInput::make('icon')->placeholder('lucide icon name'),
                    TextInput::make('text')->required(),
                ])
                ->columns(2)
                ->collapsible()
                ->defaultItems(0),
            $this->buttonsRepeater('Buttons', maxItems: 2),
        ];
    }

    public function table(Table $table): Table
    {
        return $table
            ->reorderable('sort_order')
            ->defaultSort('sort_order')
            ->columns([
                TextColumn::make('sort_order')->label('#')->sortable(),
                TextColumn::make('type')
                    ->badge()
                    ->formatStateUsing(fn ($state) => self::TYPES[$state] ?? $state)
                    ->color('primary'),
                TextColumn::make('identifier')->label('Anchor ID')->color('gray'),
                TextColumn::make('content')
                    ->label('Title')
                    ->formatStateUsing(fn ($state) => is_array($state) ? ($state['title'] ?? '—') : '—')
                    ->limit(60),
                IconColumn::make('is_active')->boolean(),
            ])
            ->headerActions([CreateAction::make()->label('Add Section')])
            ->recordActions([EditAction::make(), DeleteAction::make()]);
    }
}
