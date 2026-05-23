<?php

namespace TrackAnyDevice\Admin\Filament\Resources\Subscribers\Pages;

use TrackAnyDevice\Admin\Filament\Resources\Subscribers\SubscriberResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListSubscribers extends ListRecords
{
    protected static string $resource = SubscriberResource::class;

    protected function getHeaderActions(): array
    {
        return [CreateAction::make()];
    }
}
