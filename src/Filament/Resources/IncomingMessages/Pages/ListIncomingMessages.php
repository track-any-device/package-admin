<?php

namespace TrackAnyDevice\Admin\Filament\Resources\IncomingMessages\Pages;

use TrackAnyDevice\Admin\Filament\Resources\IncomingMessages\IncomingMessageResource;
use Filament\Resources\Pages\ListRecords;

class ListIncomingMessages extends ListRecords
{
    protected static string $resource = IncomingMessageResource::class;
}
