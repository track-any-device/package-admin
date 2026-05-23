<?php

namespace TrackAnyDevice\Admin\Filament\Resources\PolicyVersions\Pages;

use TrackAnyDevice\Admin\Filament\Resources\PolicyVersions\PolicyVersionResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListPolicyVersions extends ListRecords
{
    protected static string $resource = PolicyVersionResource::class;

    protected function getHeaderActions(): array
    {
        return [CreateAction::make()];
    }
}
