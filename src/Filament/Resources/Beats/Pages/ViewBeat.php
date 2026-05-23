<?php

namespace TrackAnyDevice\Admin\Filament\Resources\Beats\Pages;

use TrackAnyDevice\Admin\Filament\Resources\Beats\BeatResource;
use TrackAnyDevice\Core\Models\BeatTemplate;
use Filament\Actions\Action;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ViewRecord;

class ViewBeat extends ViewRecord
{
    protected static string $resource = BeatResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
            $this->recordAsTemplateAction(),
            $this->updateTemplateFromBeatAction(),
        ];
    }

    /**
     * "Record as template" — capture this beat's polygon into a new
     * BeatTemplate row. The beat itself is linked to the new template
     * via beat_template_id so future template edits propagate back.
     */
    private function recordAsTemplateAction(): Action
    {
        return Action::make('recordAsTemplate')
            ->label('Record as template')
            ->icon('heroicon-o-bookmark-square')
            ->color('success')
            ->schema([
                TextInput::make('name')
                    ->label('Template name')
                    ->required()
                    ->maxLength(150)
                    ->default(fn () => $this->record->name.' Template'),
                Textarea::make('description')
                    ->label('Description (optional)')
                    ->rows(2),
            ])
            ->action(function (array $data): void {
                $beat = $this->record;

                $template = BeatTemplate::create([
                    'name' => $data['name'],
                    'description' => $data['description'] ?? null,
                    'geo_fence_type' => $beat->geo_fence_type,
                    'coordinates' => $beat->coordinates,
                    'created_by' => auth()->id(),
                    'source_beat_id' => $beat->id,
                    'is_active' => true,
                    'version' => 1,
                ]);

                $beat->forceFill(['beat_template_id' => $template->id])->save();

                Notification::make()
                    ->title("Template '{$template->name}' created")
                    ->success()
                    ->body('This beat is now linked to the new template. Future template edits will sync back to this beat automatically.')
                    ->send();
            });
    }

    /**
     * "Update existing template from this beat" — push this beat's
     * current polygon into a template the admin picks from a
     * dropdown, then re-sync every other beat using that template.
     */
    private function updateTemplateFromBeatAction(): Action
    {
        return Action::make('updateTemplateFromBeat')
            ->label('Update existing template')
            ->icon('heroicon-o-arrow-path')
            ->color('warning')
            ->schema([
                Select::make('beat_template_id')
                    ->label('Template to update')
                    ->options(fn () => BeatTemplate::orderBy('name')->pluck('name', 'id'))
                    ->required()
                    ->searchable()
                    ->preload(),
            ])
            ->action(function (array $data): void {
                $beat = $this->record;
                $template = BeatTemplate::findOrFail($data['beat_template_id']);

                $template->forceFill([
                    'coordinates' => $beat->coordinates,
                    'geo_fence_type' => $beat->geo_fence_type,
                ])->save();

                $beat->forceFill(['beat_template_id' => $template->id])->save();

                // The observer's saved() hook already re-syncs every
                // linked beat when the geometry changes — re-load here
                // so the notification reflects the correct count.
                $template->refresh();
                $synced = $template->beats()->count();

                Notification::make()
                    ->title("Template '{$template->name}' updated")
                    ->success()
                    ->body("All {$synced} beat(s) using this template have been re-synced to the new polygon.")
                    ->send();
            });
    }
}
