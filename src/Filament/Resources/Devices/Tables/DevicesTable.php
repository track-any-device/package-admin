<?php

namespace TrackAnyDevice\Admin\Filament\Resources\Devices\Tables;

use TrackAnyDevice\Core\Enums\DeviceStatus;
use TrackAnyDevice\Core\Enums\OnboardingStatus;
use TrackAnyDevice\Core\Jobs\OnboardDeviceJob;
use TrackAnyDevice\Core\Models\Device;
use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Notifications\Notification;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;

class DevicesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('tenant.name')
                    ->label('Tenant')
                    ->placeholder('— Unassigned —')
                    ->sortable()
                    ->toggleable(),

                TextColumn::make('imei')
                    ->label('IMEI')
                    ->searchable()
                    ->copyable()
                    ->fontFamily('mono'),

                TextColumn::make('gsm_number')
                    ->label('GSM')
                    ->searchable()
                    ->copyable()
                    ->placeholder('—'),

                TextColumn::make('name')
                    ->searchable()
                    ->sortable(),

                IconColumn::make('is_approved')
                    ->label('Approved')
                    ->boolean()
                    ->trueIcon('heroicon-o-check-circle')
                    ->falseIcon('heroicon-o-x-circle')
                    ->trueColor('success')
                    ->falseColor('danger'),

                IconColumn::make('is_visible_to_tenant')
                    ->label('Visible')
                    ->boolean()
                    ->trueIcon('heroicon-o-eye')
                    ->falseIcon('heroicon-o-eye-slash')
                    ->trueColor('success')
                    ->falseColor('gray')
                    ->toggleable(),

                TextColumn::make('status')
                    ->badge()
                    ->formatStateUsing(fn (DeviceStatus $state) => $state->label())
                    ->color(fn (DeviceStatus $state) => $state->color()),

                TextColumn::make('onboarding_status')
                    ->label('Onboarding')
                    ->badge()
                    ->formatStateUsing(fn (?OnboardingStatus $state) => $state?->label() ?? 'Pending')
                    ->color(fn (?OnboardingStatus $state) => $state?->color() ?? 'gray'),

                TextColumn::make('battery_level')
                    ->label('Battery')
                    ->suffix('%')
                    ->sortable()
                    ->placeholder('—'),

                TextColumn::make('last_seen_at')
                    ->label('Last Seen')
                    ->dateTime('d M Y, H:i')
                    ->sortable()
                    ->placeholder('Never'),

                TextColumn::make('last_update_requested_at')
                    ->label('Last Update Requested')
                    ->dateTime('d M Y, H:i')
                    ->sortable()
                    ->placeholder('—')
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('deviceType.name')
                    ->label('Type')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->options(collect(DeviceStatus::cases())->mapWithKeys(
                        fn (DeviceStatus $s) => [$s->value => $s->label()]
                    )),

                SelectFilter::make('device_type_id')
                    ->label('Device Type')
                    ->relationship('deviceType', 'name'),

                TernaryFilter::make('is_approved')
                    ->label('Approval')
                    ->trueLabel('Approved only')
                    ->falseLabel('Pending approval'),

                TernaryFilter::make('is_visible_to_tenant')
                    ->label('Visibility')
                    ->trueLabel('Visible to tenant')
                    ->falseLabel('Hidden from tenant'),

                TernaryFilter::make('tenant_id')
                    ->label('Stock')
                    ->queries(
                        true: fn ($q) => $q->whereNull('tenant_id'),
                        false: fn ($q) => $q->whereNotNull('tenant_id'),
                    )
                    ->trueLabel('Stock only')
                    ->falseLabel('Assigned only'),
            ])
            ->recordActions([
                ViewAction::make(),
                EditAction::make(),
                Action::make('onboard')
                    ->label('Onboard')
                    ->icon('heroicon-o-rocket-launch')
                    ->color('primary')
                    ->visible(fn (Device $record) => ! ($record->is_approved && $record->onboarding_status === OnboardingStatus::Verified))
                    ->requiresConfirmation()
                    ->action(function (Device $record) {
                        $record->update(['is_approved' => true]);

                        OnboardDeviceJob::dispatch($record->id);

                        Notification::make()
                            ->title('Device onboarding started')
                            ->body("'{$record->name}' has been approved and onboarding commands are being dispatched.")
                            ->success()
                            ->send();
                    }),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('name');
    }
}
