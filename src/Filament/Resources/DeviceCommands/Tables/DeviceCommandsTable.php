<?php

namespace TrackAnyDevice\Admin\Filament\Resources\DeviceCommands\Tables;

use TrackAnyDevice\Core\Enums\DeviceCommandStatus;
use TrackAnyDevice\Core\Models\DeviceCommand;
use TrackAnyDevice\Core\Services\DeviceCommandService;
use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\ViewAction;
use Filament\Notifications\Notification;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class DeviceCommandsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('created_at')
                    ->label('Created')
                    ->dateTime('d M Y, H:i:s')
                    ->sortable(),

                TextColumn::make('device.name')
                    ->label('Device')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('device.imei')
                    ->label('IMEI')
                    ->searchable()
                    ->fontFamily('mono')
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('command_type')
                    ->label('Command')
                    ->searchable()
                    ->badge()
                    ->color('gray'),

                TextColumn::make('command_payload')
                    ->label('Payload')
                    ->limit(40)
                    ->tooltip(fn (DeviceCommand $r) => $r->command_payload)
                    ->fontFamily('mono')
                    ->color('gray'),

                TextColumn::make('channel')
                    ->badge()
                    ->color('info'),

                TextColumn::make('status')
                    ->badge()
                    ->formatStateUsing(fn (DeviceCommandStatus $state) => $state->label())
                    ->color(fn (DeviceCommandStatus $state) => $state->color()),

                TextColumn::make('requestedBy.name')
                    ->label('Requested By')
                    ->placeholder('System')
                    ->toggleable(),

                TextColumn::make('sent_at')
                    ->label('Sent')
                    ->dateTime('d M Y, H:i')
                    ->placeholder('—')
                    ->sortable(),

                TextColumn::make('response')
                    ->label('Response')
                    ->limit(30)
                    ->tooltip(fn (DeviceCommand $r) => $r->response)
                    ->placeholder('—')
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('failed_reason')
                    ->label('Failure Reason')
                    ->limit(30)
                    ->color('danger')
                    ->tooltip(fn (DeviceCommand $r) => $r->failed_reason)
                    ->placeholder('—')
                    ->toggleable(),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->options(collect(DeviceCommandStatus::cases())->mapWithKeys(
                        fn (DeviceCommandStatus $s) => [$s->value => $s->label()]
                    )),

                SelectFilter::make('channel')
                    ->options([
                        'sms' => 'SMS',
                        'tcp' => 'TCP',
                        'http' => 'HTTP',
                    ]),

                SelectFilter::make('command_type')
                    ->searchable()
                    ->options(
                        fn () => DeviceCommand::query()
                            ->distinct()
                            ->orderBy('command_type')
                            ->pluck('command_type', 'command_type')
                            ->all()
                    ),

                SelectFilter::make('device_id')
                    ->label('Device')
                    ->relationship('device', 'name')
                    ->searchable()
                    ->preload(),
            ])
            ->recordActions([
                ViewAction::make(),
                Action::make('cancel')
                    ->label('Cancel')
                    ->icon('heroicon-o-x-circle')
                    ->color('danger')
                    ->requiresConfirmation()
                    ->visible(fn (DeviceCommand $r) => ! $r->isTerminal())
                    ->action(function (DeviceCommand $r) {
                        app(DeviceCommandService::class)->cancel($r);
                        Notification::make()->title('Command cancelled')->success()->send();
                    }),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('created_at', 'desc');
    }
}
