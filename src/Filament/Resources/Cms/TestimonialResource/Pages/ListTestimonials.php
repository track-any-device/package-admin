<?php

namespace TrackAnyDevice\Admin\Filament\Resources\Cms\TestimonialResource\Pages;

use TrackAnyDevice\Admin\Filament\Resources\Cms\TestimonialResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListTestimonials extends ListRecords
{
    protected static string $resource = TestimonialResource::class;

    protected function getHeaderActions(): array
    {
        return [CreateAction::make()];
    }
}
