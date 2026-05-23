<?php

namespace TrackAnyDevice\Admin\Filament\Resources\Cms;

use TrackAnyDevice\Admin\Filament\Resources\Cms\NavLinkResource\Pages\ListNavLinks;
use TrackAnyDevice\Core\Models\NavLink;
use BackedEnum;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class NavLinkResource extends Resource
{
    protected static ?string $model = NavLink::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedLink;

    protected static string|\UnitEnum|null $navigationGroup = 'CMS';

    protected static ?string $navigationLabel = 'Nav Links';

    protected static ?int $navigationSort = 1;

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            TextInput::make('label')->required()->maxLength(60),
            TextInput::make('href')->required()->maxLength(255)->placeholder('/store or #explore-more'),
            Select::make('placement')
                ->options([
                    NavLink::PLACEMENT_HEADER => 'Header (top nav)',
                    NavLink::PLACEMENT_FOOTER_QUICK => 'Footer · Quick Links',
                    NavLink::PLACEMENT_FOOTER_SUPPORT => 'Footer · Support',
                    NavLink::PLACEMENT_FOOTER_LEGAL => 'Footer · Legal',
                ])
                ->default(NavLink::PLACEMENT_HEADER)
                ->required()
                ->native(false)
                ->helperText('Where this link is rendered on the public web layout.'),
            Select::make('target')
                ->options(['_self' => 'Same tab', '_blank' => 'New tab'])
                ->default('_self')
                ->required(),
            TextInput::make('sort_order')->numeric()->default(0),
            Toggle::make('is_active')->default(true),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->reorderable('sort_order')
            ->defaultSort('sort_order')
            ->columns([
                TextColumn::make('sort_order')->label('#')->sortable(),
                TextColumn::make('label')->searchable(),
                TextColumn::make('placement')
                    ->badge()
                    ->color(fn (?string $state) => match ($state) {
                        NavLink::PLACEMENT_HEADER => 'primary',
                        NavLink::PLACEMENT_FOOTER_LEGAL => 'danger',
                        default => 'info',
                    })
                    ->formatStateUsing(fn (?string $state) => match ($state) {
                        NavLink::PLACEMENT_FOOTER_QUICK => 'Footer · Quick',
                        NavLink::PLACEMENT_FOOTER_SUPPORT => 'Footer · Support',
                        NavLink::PLACEMENT_FOOTER_LEGAL => 'Footer · Legal',
                        default => 'Header',
                    }),
                TextColumn::make('href')->copyable()->color('gray'),
                TextColumn::make('target')->badge()->color(fn ($state) => $state === '_blank' ? 'warning' : 'gray'),
                IconColumn::make('is_active')->boolean(),
            ])
            ->filters([
                SelectFilter::make('placement')
                    ->options([
                        NavLink::PLACEMENT_HEADER => 'Header',
                        NavLink::PLACEMENT_FOOTER_QUICK => 'Footer · Quick',
                        NavLink::PLACEMENT_FOOTER_SUPPORT => 'Footer · Support',
                        NavLink::PLACEMENT_FOOTER_LEGAL => 'Footer · Legal',
                    ]),
            ])
            ->recordActions([EditAction::make()])
            ->toolbarActions([BulkActionGroup::make([DeleteBulkAction::make()])]);
    }

    public static function getPages(): array
    {
        return ['index' => ListNavLinks::route('/')];
    }
}
