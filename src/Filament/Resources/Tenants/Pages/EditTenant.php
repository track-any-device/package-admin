<?php

namespace TrackAnyDevice\Admin\Filament\Resources\Tenants\Pages;

use TrackAnyDevice\Admin\Filament\Resources\Tenants\TenantResource;
use Filament\Actions\Action;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditTenant extends EditRecord
{
    protected static string $resource = TenantResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Action::make('view')
                ->label('Back to View')
                ->icon('heroicon-o-arrow-left')
                ->color('gray')
                ->url(fn () => TenantResource::getUrl('view', ['record' => $this->getRecord()])),

            DeleteAction::make(),
        ];
    }

    /** After saving, redirect to the view page so the relation tabs are visible. */
    protected function getRedirectUrl(): string
    {
        return TenantResource::getUrl('view', ['record' => $this->getRecord()]);
    }
}
