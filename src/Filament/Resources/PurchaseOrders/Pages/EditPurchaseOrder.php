<?php

namespace TrackAnyDevice\Admin\Filament\Resources\PurchaseOrders\Pages;

use TrackAnyDevice\Admin\Filament\Resources\PurchaseOrders\PurchaseOrderResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditPurchaseOrder extends EditRecord
{
    protected static string $resource = PurchaseOrderResource::class;

    protected function getHeaderActions(): array
    {
        return [DeleteAction::make()];
    }
}
