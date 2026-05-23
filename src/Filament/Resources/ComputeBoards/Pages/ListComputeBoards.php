<?php

namespace TrackAnyDevice\Admin\Filament\Resources\ComputeBoards\Pages;

use TrackAnyDevice\Admin\Filament\Resources\ComputeBoards\ComputeBoardResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListComputeBoards extends ListRecords
{
    protected static string $resource = ComputeBoardResource::class;

    protected function getHeaderActions(): array
    {
        return [CreateAction::make()];
    }
}
