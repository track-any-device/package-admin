<?php

namespace TrackAnyDevice\Admin\Filament\Resources\Signals;

use TrackAnyDevice\Admin\Filament\Resources\Signals\Pages\ListSignals;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Support\Icons\Heroicon;
use TrackAnyDevice\Admin\Concerns\HasDepartmentAccess;
use TrackAnyDevice\Core\Enums\StaffDepartment;

/**
 * Read-only Filament resource for the InfluxDB-backed signal stream.
 *
 * Signals are time-series points (one row per device event) stored in the
 * `signal` measurement. The ListSignals page renders them via SignalService;
 * there is no create/edit because signals are produced by drivers, not users.
 */
class SignalResource extends Resource
{
    use HasDepartmentAccess;

    protected static ?string $model = null;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedBolt;

    protected static string|\UnitEnum|null $navigationGroup = 'System';

    protected static ?string $modelLabel = 'Signal';

    protected static ?string $pluralModelLabel = 'Signals';

    protected static ?string $slug = 'signals';

    /** @return list<StaffDepartment> */
    public static function getAllowedDepartments(): array
    {
        return [StaffDepartment::CoreTeam];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListSignals::route('/'),
        ];
    }
}
