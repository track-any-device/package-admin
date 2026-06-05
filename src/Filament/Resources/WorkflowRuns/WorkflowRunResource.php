<?php

namespace TrackAnyDevice\Admin\Filament\Resources\WorkflowRuns;

use TrackAnyDevice\Admin\Filament\Resources\WorkflowRuns\Pages\ListWorkflowRuns;
use TrackAnyDevice\Admin\Filament\Resources\WorkflowRuns\Pages\ViewWorkflowRun;
use TrackAnyDevice\Admin\Filament\Resources\WorkflowRuns\Tables\WorkflowRunsTable;
use TrackAnyDevice\Core\Models\WorkflowRun;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use TrackAnyDevice\Admin\Concerns\HasDepartmentAccess;
use TrackAnyDevice\Core\Enums\StaffDepartment;

class WorkflowRunResource extends Resource
{
    use HasDepartmentAccess;
    protected static ?string $model = WorkflowRun::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedPlayCircle;

    protected static string|\UnitEnum|null $navigationGroup = 'Operations';

    protected static ?int $navigationSort = 2;

    public static function canCreate(): bool
    {
        return false;
    }

    public static function table(Table $table): Table
    {
        return WorkflowRunsTable::configure($table);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListWorkflowRuns::route('/'),
            'view' => ViewWorkflowRun::route('/{record}'),
        ];
    }

    protected static function getAllowedDepartments(): array
    {
        return [StaffDepartment::CoreTeam];
    }
}
