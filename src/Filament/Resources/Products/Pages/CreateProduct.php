<?php

namespace TrackAnyDevice\Admin\Filament\Resources\Products\Pages;

use TrackAnyDevice\Admin\Filament\Resources\Products\ProductResource;
use TrackAnyDevice\Core\Enums\ProductType;
use Filament\Resources\Pages\CreateRecord;

class CreateProduct extends CreateRecord
{
    protected static string $resource = ProductResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['productable_type'] = ProductType::from($data['product_type'])->modelClass();

        return $data;
    }
}
