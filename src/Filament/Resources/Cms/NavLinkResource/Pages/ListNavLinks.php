<?php

namespace TrackAnyDevice\Admin\Filament\Resources\Cms\NavLinkResource\Pages;

use TrackAnyDevice\Admin\Filament\Resources\Cms\NavLinkResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListNavLinks extends ListRecords
{
    protected static string $resource = NavLinkResource::class;

    protected function getHeaderActions(): array
    {
        return [CreateAction::make()];
    }
}
