<?php

namespace TrackAnyDevice\Admin\Filament\Resources\Workflows;

use TrackAnyDevice\Admin\Filament\Resources\Workflows\Pages\CreateWorkflow;
use TrackAnyDevice\Admin\Filament\Resources\Workflows\Pages\EditWorkflow;
use TrackAnyDevice\Admin\Filament\Resources\Workflows\Pages\ListWorkflows;
use TrackAnyDevice\Admin\Filament\Resources\Workflows\Pages\ViewWorkflow;
use TrackAnyDevice\Admin\Filament\Resources\Workflows\Schemas\WorkflowForm;
use TrackAnyDevice\Admin\Filament\Resources\Workflows\Tables\WorkflowsTable;
use TrackAnyDevice\Core\Models\Workflow;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class WorkflowResource extends Resource
{
    protected static ?string $model = Workflow::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedBolt;

    protected static string|\UnitEnum|null $navigationGroup = 'Automation';

    protected static ?int $navigationSort = 1;

    public static function form(Schema $schema): Schema
    {
        return WorkflowForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return WorkflowsTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListWorkflows::route('/'),
            'create' => CreateWorkflow::route('/create'),
            'view' => ViewWorkflow::route('/{record}'),
            'edit' => EditWorkflow::route('/{record}/edit'),
        ];
    }
}
