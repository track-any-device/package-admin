<?php

namespace TrackAnyDevice\Admin\Filament\Resources\Warehouses\Pages;

use TrackAnyDevice\Admin\Filament\Resources\Warehouses\WarehouseResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditWarehouse extends EditRecord
{
    protected static string $resource = WarehouseResource::class;

    protected function getHeaderActions(): array
    {
        return [DeleteAction::make()];
    }
}
