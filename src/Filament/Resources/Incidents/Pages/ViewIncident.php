<?php

namespace TrackAnyDevice\Admin\Filament\Resources\Incidents\Pages;

use TrackAnyDevice\Core\Enums\IncidentStatus;
use TrackAnyDevice\Admin\Filament\Resources\Incidents\IncidentResource;
use TrackAnyDevice\Core\Models\Incident;
use TrackAnyDevice\Core\Services\IncidentService;
use Filament\Actions\Action;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Textarea;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ViewRecord;

class ViewIncident extends ViewRecord
{
    protected static string $resource = IncidentResource::class;

    protected function getHeaderActions(): array
    {
        /** @var Incident $incident */
        $incident = $this->record;
        $service = app(IncidentService::class);

        return [
            Action::make('acknowledge')
                ->label('Acknowledge')
                ->icon('heroicon-o-hand-raised')
                ->color('warning')
                ->visible(fn () => $incident->status->canTransitionTo(IncidentStatus::Acknowledged))
                ->action(function () use ($incident, $service) {
                    $service->acknowledge($incident, auth()->user());
                    $this->record->refresh();
                    Notification::make()->title('Incident acknowledged')->success()->send();
                }),

            Action::make('resolve')
                ->label('Resolve')
                ->icon('heroicon-o-check-circle')
                ->color('success')
                ->visible(fn () => $incident->status->canTransitionTo(IncidentStatus::Resolved))
                ->form([
                    Textarea::make('resolution_notes')
                        ->label('Resolution notes')
                        ->rows(3),
                ])
                ->action(function (array $data) use ($incident, $service) {
                    $service->resolve($incident, auth()->user(), $data['resolution_notes'] ?? null);
                    $this->record->refresh();
                    Notification::make()->title('Incident resolved')->success()->send();
                }),

            Action::make('dismiss')
                ->label('Dismiss')
                ->icon('heroicon-o-x-circle')
                ->color('gray')
                ->visible(fn () => ! $incident->isSos() && $incident->status->canTransitionTo(IncidentStatus::Dismissed))
                ->requiresConfirmation()
                ->action(function () use ($incident, $service) {
                    $service->dismiss($incident, auth()->user());
                    $this->record->refresh();
                    Notification::make()->title('Incident dismissed')->success()->send();
                }),

            Action::make('escalate')
                ->label('Escalate')
                ->icon('heroicon-o-arrow-up-circle')
                ->color('danger')
                ->visible(fn () => $incident->status->canTransitionTo(IncidentStatus::Escalated))
                ->requiresConfirmation()
                ->action(function () use ($incident, $service) {
                    $service->escalate($incident, auth()->user());
                    $this->record->refresh();
                    Notification::make()->title('Incident escalated')->warning()->send();
                }),

            EditAction::make(),
        ];
    }
}
