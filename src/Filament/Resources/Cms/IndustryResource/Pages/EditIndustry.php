<?php

namespace TrackAnyDevice\Admin\Filament\Resources\Cms\IndustryResource\Pages;

use TrackAnyDevice\Admin\Filament\Resources\Cms\IndustryResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditIndustry extends EditRecord
{
    protected static string $resource = IndustryResource::class;

    protected function getHeaderActions(): array
    {
        return [DeleteAction::make()];
    }
}
