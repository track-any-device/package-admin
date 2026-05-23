<?php

namespace TrackAnyDevice\Admin\Filament\Resources\Cms\BlogResource\Pages;

use TrackAnyDevice\Admin\Filament\Resources\Cms\BlogResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListBlogs extends ListRecords
{
    protected static string $resource = BlogResource::class;

    protected function getHeaderActions(): array
    {
        return [CreateAction::make()];
    }
}
