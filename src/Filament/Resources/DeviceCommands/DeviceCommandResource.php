<?php

namespace TrackAnyDevice\Admin\Filament\Resources\DeviceCommands;

use TrackAnyDevice\Admin\Filament\Resources\DeviceCommands\Pages\ListDeviceCommands;
use TrackAnyDevice\Admin\Filament\Resources\DeviceCommands\Pages\ViewDeviceCommand;
use TrackAnyDevice\Admin\Filament\Resources\DeviceCommands\Schemas\DeviceCommandForm;
use TrackAnyDevice\Admin\Filament\Resources\DeviceCommands\Tables\DeviceCommandsTable;
use TrackAnyDevice\Core\Models\DeviceCommand;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class DeviceCommandResource extends Resource
{
    protected static ?string $model = DeviceCommand::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedPaperAirplane;

    protected static string|\UnitEnum|null $navigationGroup = 'Messages';

    protected static ?string $navigationLabel = 'Outgoing';

    protected static ?string $modelLabel = 'Outgoing Message';

    protected static ?string $pluralModelLabel = 'Outgoing Messages';

    protected static ?int $navigationSort = 2;

    public static function form(Schema $schema): Schema
    {
        return DeviceCommandForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return DeviceCommandsTable::configure($table);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListDeviceCommands::route('/'),
            'view' => ViewDeviceCommand::route('/{record}'),
        ];
    }

    public static function canCreate(): bool
    {
        return false;
    }

    public static function getNavigationBadge(): ?string
    {
        $count = DeviceCommand::whereIn('status', ['pending', 'queued'])->count();

        return $count > 0 ? (string) $count : null;
    }

    public static function getNavigationBadgeColor(): ?string
    {
        return DeviceCommand::where('status', 'failed')->exists() ? 'danger' : 'info';
    }
}
