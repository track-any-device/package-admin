<?php

namespace TrackAnyDevice\Admin\Filament\Resources\Products\Pages;

use TrackAnyDevice\Admin\Filament\Resources\Products\ProductResource;
use TrackAnyDevice\Core\Enums\ProductType;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditProduct extends EditRecord
{
    protected static string $resource = ProductResource::class;

    protected function getHeaderActions(): array
    {
        return [DeleteAction::make()];
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        $data['productable_type'] = ProductType::from($data['product_type'])->modelClass();

        return $data;
    }
}
