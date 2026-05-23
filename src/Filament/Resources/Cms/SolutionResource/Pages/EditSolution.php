<?php

namespace TrackAnyDevice\Admin\Filament\Resources\Cms\SolutionResource\Pages;

use TrackAnyDevice\Admin\Filament\Resources\Cms\SolutionResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditSolution extends EditRecord
{
    protected static string $resource = SolutionResource::class;

    protected function getHeaderActions(): array
    {
        return [DeleteAction::make()];
    }
}
