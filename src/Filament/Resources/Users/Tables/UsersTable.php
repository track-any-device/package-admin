<?php

namespace TrackAnyDevice\Admin\Filament\Resources\Users\Tables;

use TrackAnyDevice\Core\Enums\Role;
use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Notifications\Notification;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Password;

class UsersTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('email')
                    ->searchable()
                    ->sortable()
                    ->copyable(),

                BadgeColumn::make('role')
                    ->colors([
                        'danger' => Role::Admin->value,
                        'warning' => Role::Supervisor->value,
                        'success' => Role::Staff->value,
                        'info' => Role::TenantUser->value,
                        'gray' => Role::User->value,
                    ])
                    ->formatStateUsing(fn (Role $state): string => $state->label())
                    ->sortable(),

                TextColumn::make('primary_contact')
                    ->label('Phone')
                    ->placeholder('—')
                    ->searchable()
                    ->toggleable(),

                TextColumn::make('public_contact')
                    ->label('Public Contact')
                    ->placeholder('—')
                    ->searchable()
                    ->toggleable(),

                TextColumn::make('email_verified_at')
                    ->label('Verified')
                    ->dateTime('d M Y')
                    ->placeholder('Not verified')
                    ->sortable(),

                TextColumn::make('created_at')
                    ->label('Joined')
                    ->since()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('role')
                    ->options(collect(Role::cases())->mapWithKeys(
                        fn (Role $r) => [$r->value => $r->label()]
                    )->all()),
            ])
            ->recordActions([
                Action::make('resend_invite')
                    ->label('Resend Invite')
                    ->icon('heroicon-o-envelope')
                    ->color('info')
                    ->requiresConfirmation()
                    ->modalHeading('Resend invitation email?')
                    ->modalDescription(fn ($record) => "Send a password-reset link to {$record->email}.")
                    ->action(function ($record) {
                        $status = Password::sendResetLink(['email' => $record->email]);
                        Notification::make()
                            ->title($status === Password::ResetLinkSent ? 'Invitation sent' : 'Could not send email')
                            ->body($status === Password::ResetLinkSent
                                ? "A set-password link was emailed to {$record->email}."
                                : 'Check mail configuration.')
                            ->status($status === Password::ResetLinkSent ? 'success' : 'warning')
                            ->send();
                    }),

                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('created_at', 'desc');
    }
}
