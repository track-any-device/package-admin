<?php

namespace TrackAnyDevice\Admin\Filament\Resources\Subscribers;

use TrackAnyDevice\Admin\Filament\Resources\Subscribers\Pages\ListSubscribers;
use TrackAnyDevice\Core\Models\Subscriber;
use BackedEnum;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;

class SubscriberResource extends Resource
{
    protected static ?string $model = Subscriber::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedEnvelope;

    protected static string|\UnitEnum|null $navigationGroup = 'CMS';

    protected static ?string $navigationLabel = 'Subscribers';

    protected static ?int $navigationSort = 5;

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            TextInput::make('email')->email()->required()->maxLength(255)->unique(ignoreRecord: true),
            TextInput::make('name')->maxLength(120),
            Select::make('source')
                ->options([
                    'footer' => 'Footer form',
                    'contact' => 'Contact page',
                    'api' => 'API / Integration',
                    'manual' => 'Added by admin',
                ])
                ->default('manual')
                ->required(),
            DateTimePicker::make('subscribed_at')->default(now()),
            DateTimePicker::make('unsubscribed_at')->helperText('Set to mark this subscriber as opted-out.'),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->defaultSort('created_at', 'desc')
            ->columns([
                TextColumn::make('email')->searchable()->copyable(),
                TextColumn::make('name')->placeholder('—')->searchable(),
                TextColumn::make('source')->badge()
                    ->color(fn (?string $state) => match ($state) {
                        'footer' => 'primary',
                        'contact' => 'info',
                        'api' => 'success',
                        'manual' => 'gray',
                        default => 'gray',
                    }),
                IconColumn::make('is_active')
                    ->label('Active')
                    ->boolean()
                    ->state(fn (Subscriber $r) => $r->unsubscribed_at === null),
                TextColumn::make('subscribed_at')->dateTime('d M Y H:i')->sortable(),
                TextColumn::make('unsubscribed_at')->dateTime('d M Y H:i')->placeholder('—')->sortable(),
                TextColumn::make('ip_address')->placeholder('—')->color('gray')->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                TernaryFilter::make('is_active')
                    ->label('Active')
                    ->placeholder('All')
                    ->trueLabel('Active only')
                    ->falseLabel('Unsubscribed only')
                    ->queries(
                        true: fn ($q) => $q->whereNull('unsubscribed_at'),
                        false: fn ($q) => $q->whereNotNull('unsubscribed_at'),
                    ),
            ])
            ->recordActions([EditAction::make()])
            ->toolbarActions([BulkActionGroup::make([DeleteBulkAction::make()])]);
    }

    public static function getPages(): array
    {
        return ['index' => ListSubscribers::route('/')];
    }

    public static function getNavigationBadge(): ?string
    {
        $count = Subscriber::active()->count();

        return $count > 0 ? (string) $count : null;
    }
}
