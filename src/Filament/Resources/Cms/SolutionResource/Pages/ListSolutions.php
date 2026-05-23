<?php

namespace TrackAnyDevice\Admin\Filament\Resources\Cms\SolutionResource\Pages;

use TrackAnyDevice\Admin\Filament\Resources\Cms\SolutionResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListSolutions extends ListRecords
{
    protected static string $resource = SolutionResource::class;

    protected function getHeaderActions(): array
    {
        return [CreateAction::make()];
    }
}
