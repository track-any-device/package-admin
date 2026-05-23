<?php

namespace TrackAnyDevice\Admin\Filament\Resources\ComputeBoards\Pages;

use TrackAnyDevice\Admin\Filament\Resources\ComputeBoards\ComputeBoardResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditComputeBoard extends EditRecord
{
    protected static string $resource = ComputeBoardResource::class;

    protected function getHeaderActions(): array
    {
        return [DeleteAction::make()];
    }
}
