<?php

namespace TrackAnyDevice\Admin\Filament\Resources\IncomingMessages;

use TrackAnyDevice\Admin\Filament\Resources\IncomingMessages\Pages\ListIncomingMessages;
use TrackAnyDevice\Admin\Filament\Resources\IncomingMessages\Pages\ViewIncomingMessage;
use TrackAnyDevice\Admin\Filament\Resources\IncomingMessages\Schemas\IncomingMessageForm;
use TrackAnyDevice\Admin\Filament\Resources\IncomingMessages\Tables\IncomingMessagesTable;
use TrackAnyDevice\Core\Models\IncomingSms;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class IncomingMessageResource extends Resource
{
    protected static ?string $model = IncomingSms::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedInbox;

    protected static string|\UnitEnum|null $navigationGroup = 'Messages';

    protected static ?string $navigationLabel = 'Incoming';

    protected static ?string $modelLabel = 'Incoming Message';

    protected static ?string $pluralModelLabel = 'Incoming Messages';

    protected static ?int $navigationSort = 1;

    public static function form(Schema $schema): Schema
    {
        return IncomingMessageForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return IncomingMessagesTable::configure($table);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListIncomingMessages::route('/'),
            'view' => ViewIncomingMessage::route('/{record}'),
        ];
    }

    public static function canCreate(): bool
    {
        return false;
    }

    public static function getNavigationBadge(): ?string
    {
        $count = IncomingSms::whereNull('processed_at')->count();

        return $count > 0 ? (string) $count : null;
    }

    public static function getNavigationBadgeColor(): ?string
    {
        return IncomingSms::whereNull('processed_at')->whereNotNull('processing_error')->exists()
            ? 'danger'
            : 'warning';
    }
}
