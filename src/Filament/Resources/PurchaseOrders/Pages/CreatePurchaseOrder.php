<?php

namespace TrackAnyDevice\Admin\Filament\Resources\PurchaseOrders\Pages;

use TrackAnyDevice\Admin\Filament\Resources\PurchaseOrders\PurchaseOrderResource;
use Filament\Resources\Pages\CreateRecord;

class CreatePurchaseOrder extends CreateRecord
{
    protected static string $resource = PurchaseOrderResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['ordered_by'] = auth()->id();

        return $data;
    }
}
