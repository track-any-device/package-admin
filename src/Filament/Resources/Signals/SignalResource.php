<?php

namespace TrackAnyDevice\Admin\Filament\Resources\Signals;

use TrackAnyDevice\Admin\Filament\Resources\Signals\Pages\ListSignals;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Support\Icons\Heroicon;

/**
 * Read-only Filament resource for the InfluxDB-backed signal stream.
 *
 * Signals are time-series points (one row per device event) stored in the
 * `signal` measurement. The ListSignals page renders them via SignalService;
 * there is no create/edit because signals are produced by drivers, not users.
 */
class SignalResource extends Resource
{
    protected static ?string $model = null;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedBolt;

    protected static string|\UnitEnum|null $navigationGroup = 'Telemetry';

    protected static ?string $modelLabel = 'Signal';

    protected static ?string $pluralModelLabel = 'Signals';

    protected static ?string $slug = 'signals';

    public static function getPages(): array
    {
        return [
            'index' => ListSignals::route('/'),
        ];
    }
}
