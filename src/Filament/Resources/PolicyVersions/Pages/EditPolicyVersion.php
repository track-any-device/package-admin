<?php

namespace TrackAnyDevice\Admin\Filament\Resources\PolicyVersions\Pages;

use TrackAnyDevice\Admin\Filament\Resources\PolicyVersions\PolicyVersionResource;
use TrackAnyDevice\Core\Models\PolicyVersion;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditPolicyVersion extends EditRecord
{
    protected static string $resource = PolicyVersionResource::class;

    protected function getHeaderActions(): array
    {
        return [DeleteAction::make()];
    }

    protected function afterSave(): void
    {
        /** @var PolicyVersion $record */
        $record = $this->record;

        if ($record->is_current) {
            $record->setCurrent();
        }
    }
}
