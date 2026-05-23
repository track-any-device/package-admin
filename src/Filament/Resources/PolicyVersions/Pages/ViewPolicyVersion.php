<?php

namespace TrackAnyDevice\Admin\Filament\Resources\PolicyVersions\Pages;

use TrackAnyDevice\Admin\Filament\Resources\PolicyVersions\PolicyVersionResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewPolicyVersion extends ViewRecord
{
    protected static string $resource = PolicyVersionResource::class;

    protected function getHeaderActions(): array
    {
        return [EditAction::make()];
    }
}
