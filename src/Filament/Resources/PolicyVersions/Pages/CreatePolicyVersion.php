<?php

namespace TrackAnyDevice\Admin\Filament\Resources\PolicyVersions\Pages;

use TrackAnyDevice\Admin\Filament\Resources\PolicyVersions\PolicyVersionResource;
use TrackAnyDevice\Core\Models\PolicyVersion;
use Filament\Resources\Pages\CreateRecord;

class CreatePolicyVersion extends CreateRecord
{
    protected static string $resource = PolicyVersionResource::class;

    protected function afterCreate(): void
    {
        /** @var PolicyVersion $record */
        $record = $this->record;

        if ($record->is_current) {
            $record->setCurrent();
        }
    }
}
