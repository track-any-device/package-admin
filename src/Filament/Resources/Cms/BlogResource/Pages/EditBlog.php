<?php

namespace TrackAnyDevice\Admin\Filament\Resources\Cms\BlogResource\Pages;

use TrackAnyDevice\Admin\Filament\Resources\Cms\BlogResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditBlog extends EditRecord
{
    protected static string $resource = BlogResource::class;

    protected function getHeaderActions(): array
    {
        return [DeleteAction::make()];
    }
}
