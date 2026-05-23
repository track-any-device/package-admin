<?php

namespace TrackAnyDevice\Admin\Filament\Resources\Tenants\Pages;

use TrackAnyDevice\Admin\Filament\Resources\Tenants\TenantResource;
use Filament\Resources\Pages\CreateRecord;

class CreateTenant extends CreateRecord
{
    protected static string $resource = TenantResource::class;
}
