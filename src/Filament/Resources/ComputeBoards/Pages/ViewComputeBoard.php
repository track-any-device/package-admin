<?php

namespace TrackAnyDevice\Admin\Filament\Resources\ComputeBoards\Pages;

use TrackAnyDevice\Admin\Filament\Resources\ComputeBoards\ComputeBoardResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewComputeBoard extends ViewRecord
{
    protected static string $resource = ComputeBoardResource::class;

    protected function getHeaderActions(): array
    {
        return [EditAction::make()];
    }
}
