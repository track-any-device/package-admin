<?php

namespace TrackAnyDevice\Admin\Filament\Resources\IncomingMessages\Tables;

use TrackAnyDevice\Core\Models\IncomingSms;
use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\ViewAction;
use Filament\Notifications\Notification;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;

class IncomingMessagesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('received_at')
                    ->label('Received')
                    ->dateTime('d M Y, H:i:s')
                    ->sortable(),

                TextColumn::make('sender_number')
                    ->label('Sender')
                    ->searchable()
                    ->copyable()
                    ->fontFamily('mono'),

                TextColumn::make('raw_message')
                    ->label('Message')
                    ->limit(60)
                    ->tooltip(fn (IncomingSms $r) => $r->raw_message)
                    ->searchable(),

                TextColumn::make('source')
                    ->badge()
                    ->color(fn (?string $state) => match ($state) {
                        'gateway_api' => 'primary',
                        'twilio' => 'success',
                        'manual' => 'gray',
                        default => 'gray',
                    }),

                IconColumn::make('processed_at')
                    ->label('Processed')
                    ->boolean()
                    ->state(fn (IncomingSms $r) => $r->processed_at !== null)
                    ->trueIcon('heroicon-o-check-circle')
                    ->falseIcon('heroicon-o-clock')
                    ->trueColor('success')
                    ->falseColor('warning'),

                TextColumn::make('processing_error')
                    ->label('Error')
                    ->limit(40)
                    ->color('danger')
                    ->tooltip(fn (IncomingSms $r) => $r->processing_error)
                    ->placeholder('—')
                    ->toggleable(),

                TextColumn::make('processed_at')
                    ->label('Processed At')
                    ->dateTime('d M Y, H:i')
                    ->placeholder('—')
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                TernaryFilter::make('processed_at')
                    ->label('Status')
                    ->trueLabel('Processed only')
                    ->falseLabel('Pending only')
                    ->queries(
                        true: fn ($q) => $q->whereNotNull('processed_at'),
                        false: fn ($q) => $q->whereNull('processed_at'),
                    ),

                TernaryFilter::make('processing_error')
                    ->label('Has Error')
                    ->trueLabel('Errors only')
                    ->falseLabel('No errors')
                    ->queries(
                        true: fn ($q) => $q->whereNotNull('processing_error'),
                        false: fn ($q) => $q->whereNull('processing_error'),
                    ),

                SelectFilter::make('source')
                    ->options([
                        'gateway_api' => 'Gateway API',
                        'twilio' => 'Twilio',
                        'manual' => 'Manual',
                    ]),
            ])
            ->recordActions([
                ViewAction::make(),
                Action::make('mark_processed')
                    ->label('Mark Processed')
                    ->icon('heroicon-o-check')
                    ->color('success')
                    ->requiresConfirmation()
                    ->visible(fn (IncomingSms $r) => $r->processed_at === null)
                    ->action(function (IncomingSms $r) {
                        $r->update([
                            'processed_at' => now(),
                            'processing_error' => null,
                        ]);
                        Notification::make()->title('Message marked as processed')->success()->send();
                    }),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('received_at', 'desc');
    }
}
